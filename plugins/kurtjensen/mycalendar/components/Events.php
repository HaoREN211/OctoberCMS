<?php namespace KurtJensen\MyCalendar\Components;

use Auth;
use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use KurtJensen\MyCalendar\Models\Category;
use KurtJensen\MyCalendar\Models\Occurrence as Ocurrs;
use KurtJensen\MyCalendar\Models\Settings;
use Lang;

class Events extends ComponentBase {
	public $ComponentType = 'events';
	use \KurtJensen\MyCalendar\Traits\MyCalComponentTraits;

	public $category;
	public $usePermissions = 0;
	public $dayspast = 0;
	public $daysfuture = 0;
	public $compLink = 'Events';
	public $user_id = null;
	public $linkpage = '';
	public $relations = ['event'];

	/**
	 * Events component property
	 * @var string Sort order for events
	 */
	public $order;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.events_comp.name',
			'description' => 'kurtjensen.mycalendar::lang.events_comp.description',
		];
	}

	public function __construct($compLink = 'Events') {
		$this->compLink = $compLink;
		parent::__construct();
	}

	public function init() {
		$this->importProperties($this);
	}

	public function onRun() {
		$this->page['MyEvents'] = $this->loadEvents();
		// 	$this->mergeEvents($this->loadEvents());
	}

	public function importProperties($comp) {
		$this->linkpage = $comp->property('linkpage');
		$this->category = $comp->property('category', null);
		$this->usePermissions = $comp->property('usePermissions', 0);
		$this->order = $comp->property('descending') ? 'desc' : 'asc';
		if (!$comp->property('month') || !$comp->property('year')) {
			$this->dayspast = $comp->property('dayspast', 120);
			$this->daysfuture = $comp->property('daysfuture', 60);
		} else {
			$this->month = in_array($comp->property('month'), range(1, 12)) ? $comp->property('month') : date('m');
			$this->year = in_array($comp->property('year'), range(date('Y') - 2, date('Y') + 15)) ? $comp->property('year') : date('Y');
		}
		$this->relations = $comp->property('relations', ['event']);
	}

	public function userId() {
		if (is_null($this->user_id)) {
			$user = Auth::getUser();
			if ($user) {
				$this->user_id = $user->id;
			} else {
				$this->user_id = 0;
			}
		}
		return $this->user_id;
	}

	public function loadEvents() {
		if ($this->daysfuture || $this->dayspast) {
			$month_start = new Carbon(date('Y/m/d') . ' 00:00:00');
			$month_end = $month_start->copy()->addDays($this->daysfuture);
			$month_start->subDays($this->dayspast);
		} else {
			$month_start = new Carbon($this->year . '/' . $this->month . '/1 00:00:00');
			$month_end = $month_start->copy()->addMonth(1);
		}

		$MyEvents = [];
		$timeFormat = Settings::get('time_format', 'g:i a');

		foreach ($this->relations as $relation_name) {
			$occurs = Ocurrs::with(
				array($relation_name => function ($query) {
					$query->withOwner()
						->published();

					if ($this->category) {
						$query->whereHas('categorys', function ($q) {
							$q->where('slug', $this->category);
						});
					}

					if ($this->usePermissions) {

						$query->permisions(
							$this->userId(),
							[Settings::get('public_perm')],
							Settings::get('deny_perm')
						);
					}
				})
			)->
				where('start_at', '<', $month_end)->
				where('end_at', '>=', $month_start)->
				//wher//('relation', $relation_name)->
				orderBy('start_at', $this->order)->
				get();

			if (!$occurs) {
				return [];
			}

			$maxLen = $this->property('title_max', 100);

			foreach ($occurs as $occ) {
				if (!$occ->$relation_name) {
					continue;
				}

				$title = (strlen($occ->$relation_name->text) > 50) ? substr(strip_tags($occ->$relation_name->text), 0, $maxLen) . '...' : $occ->$relation_name->text;

				$link = $occ->$relation_name->link ?: // If model has link property use it
				($this->linkpage ? // else If component has linkpage use it
					Page::url($this->linkpage, ['slug' => $occ->$relation_name->id]) : // else Use AJAX popup
					'#EventDetail"
            	data-request="onShowEvent"
            	data-request-data="evid:' . $occ->id . ($relation_name == 'event' ? '' : ',rel:\'' . $relation_name . '\'') . '"
            	data-request-update="\'' . $this->compLink . '::details\':\'#EventDetail\'" data-toggle="modal" data-target="#myModal');
				$time = $occ->is_allday ? '(' . Lang::get('kurtjensen.mycalendar::lang.occurrence.is_allday') . ')'
				: $occ->start_at->format($timeFormat);

				$evData = [
					'name' => $occ->$relation_name->name,
					'time' => $time,
					'title' => $title,
					'link' => $link,
					'id' => $occ->id,
					'owner' => $occ->$relation_name->user_id,
					'owner_name' => $occ->$relation_name->owner_name,
					'data' => $occ->$relation_name,
				];
/*
if ($this->property('raw_data', false)) {
$data['data'] = $occ->$relation_name;
}

 */
				$MyEvents[$occ->start_at->year][$occ->start_at->month][$occ->start_at->day][] = $evData;
			}
		}
		return $MyEvents;
	}

	public function onShowEvent() {
		$e = false;

		$relation_name = post('rel') ?: 'event';

		$ocurrs = Ocurrs::with(
			array($relation_name => function ($query) {
				$query->withOwner()
					->published();

				if ($this->category) {
					$query->whereHas('categorys', function ($q) {
						$q->where('slug', $this->category);
					});
				}

				if ($this->usePermissions) {

					$query->permisions(
						$this->userId(),
						[Settings::get('public_perm')],
						Settings::get('deny_perm')
					);
				}
			})
		)->
			find(post('evid'));

		if (!$ocurrs->$relation_name) {
			return $this->page['ev'] = ['name' => Lang::get('kurtjensen.mycalendar::lang.event.error_not_found'), 'cats' => []];
		}

		$timeFormat = Settings::get('time_format', 'g:i a');
		$dateFormat = Settings::get('date_format', 'F jS, Y');

		if ($this->property('raw_data', false)) {
			return $this->page['ev_data'] = [
				'ev' => $ocurrs->$relation_name,
				'oc' => $ocurrs,
				'format' => ['t' => $timeFormat, 'd' => $dateFormat],
			];
		} else {
			return $this->page['ev'] = [
				'name' => $ocurrs->$relation_name->name,
				'date' => $ocurrs->start_at->format($dateFormat),
				'time' => $ocurrs->is_allday ? Lang::get('kurtjensen.mycalendar::lang.occurrence.is_allday')
				: $ocurrs->start_at->format($timeFormat) . ' - ' . $ocurrs->end_at->format($timeFormat),
				'link' => $ocurrs->$relation_name->link ? $ocurrs->$relation_name->link : '',
				'text' => $ocurrs->$relation_name->text,
				'cats' => $ocurrs->$relation_name->categorys->lists('name'),
				'owner' => $ocurrs->$relation_name->user_id,
				'owner_name' => $ocurrs->$relation_name->owner_name,
				'data' => $ocurrs->$relation_name,
			];
		}
	}
}
