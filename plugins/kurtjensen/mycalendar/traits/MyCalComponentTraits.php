<?php namespace KurtJensen\MyCalendar\Traits;

/**
 * MyCalComponentTraits
 * Consolidated Component Properties and Functions for easier reuse and
 * easier code management.
 *
 * @package kurtjensen\mycalendar
 * @author Kurt Jensen
 */
use Carbon\Carbon;
use Cms\Classes\Page;
use KurtJensen\MyCalendar\Components\Events;
use KurtJensen\MyCalendar\Models\Occurrence as Ocurrs;
use Lang;

trait MyCalComponentTraits {

	/**
	 * Week component property
	 * @var integer A day in the week you want to show. ( 1..31 ).
	 */
	public $day;

	/**
	 * Month / Week component property
	 * @var integer The month you want to show. ( 1..12 ).
	 */
	public $month;

	/**
	 * Month / Week component property
	 * @var integer The year you want to show.
	 */
	public $year;

	/**
	 * Month / Week component property
	 * @var integer The day that starts the weeks of the calendar. ( 0..6 )
	 */
	public $weekstart;

	/**
	 * Month / Week component property
	 *
	 * $dayprops[2017][3][15]['class' => 'yellow']
	 * @var array Array of the properties you want to put on the day indicator.
	 */
	public $dayprops;

	/**
	 * Month / Week / List component property
	 * @var string Color the calendar will be
	 */
	public $color;

	/**
	 * Month / Week component property
	 * @var boolean Include data-request-data properties in calendar output
	 */
	public $data_request = false;

	/**
	 * Month / Week / List component property
	 *          YEAR    MONTH    DAY   SEQUENCE   DATA
	 * $events[ 2017 ][   3   ][  15 ][        ][
	 *      'class' => '',
	 *		'title' => '',
	 *		'text' => '',
	 *		'link' => '',
	 *		'name' => '',
	 *    ]
	 * @var array Multi-dimention collection of events and their
	 *      data to display.
	 */
	public $events = [];

	/**
	 * Calculated
	 * @var array Headings for days of week ( Sun, Mon,Tue...)
	 */
	public $calHeadings;

	/**
	 * Calculated
	 * @var integer Month of selected time.
	 */
	public $monthNum;

	/**
	 * Calculated
	 * @var integer Day of week for selected time. ( 0..6 )
	 */
	public $running_day;

	/**
	 * Calculated
	 * @var integer Count of days in month for selected time.
	 */
	public $days_in_month;

	/**
	 * Calculated
	 * @var integer Pointer to what day is being iterated over.
	 */
	public $dayPointer;

	/**
	 * Calculated
	 * @var integer Day in previous month to start display on when
	 * selected month does not start on first day of week.
	 */
	public $prevMonthStartDay;

	/**
	 * Calculated
	 * @var string Translated Month Heading
	 */
	public $monthTitle;

	/**
	 * Calculated
	 * @var Carbon\Carbon Date to jump to for displaying next in sequence.
	 */
	public $linkNext = null;

	/**
	 * Calculated
	 * @var Carbon\Carbon Date to jump to for displaying previous in sequence.
	 */
	public $linkPrevious = null;

	/**
	 * Calculated
	 * @var Carbon\Carbon Date currently displayed.
	 */
	public $viewTime;

	public $langPath = 'kurtjensen.mycalendar::lang.';

	public function defineProperties() {
		$type = is_array($this->ComponentType) ? $this->ComponentType[0] : $this->ComponentType;
		$properties = [];
		switch ($type) {
		case 'events':
			$properties = [
				'linkpage' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.linkpage_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.linkpage_desc',
					'type' => 'dropdown',
					//'options' => $this->getpageOptions(),
					'default' => '',
					'group' => 'kurtjensen.mycalendar::lang.events_comp.linkpage_group',
				],
				'title_max' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.title_max_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.title_max_description',
					'default' => 100,
				],
				'usePermissions' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.permissions_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.permissions_description',
					'type' => 'dropdown',
					'default' => 0,
					'options' => [
						0 => 'kurtjensen.mycalendar::lang.events_comp.opt_no',
						1 => 'kurtjensen.mycalendar::lang.events_comp.opt_yes',
					],
				],
				'category' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.category_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.category_description',
					'default' => '{{ :cat }}',
				],
				'month' => [
					'title' => 'kurtjensen.mycalendar::lang.month.month_title',
					'description' => 'kurtjensen.mycalendar::lang.month.month_description',
					'default' => '{{ :month }}',
				],
				'year' => [
					'title' => 'kurtjensen.mycalendar::lang.month.year_title',
					'description' => 'kurtjensen.mycalendar::lang.month.year_description',
					'default' => '{{ :year }}',
				],
				'dayspast' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.past_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.past_description',
					'default' => 0,
				],
				'daysfuture' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.future_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.future_description',
					'default' => 60,
				],
				'relations' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.relations_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.relations_description',
					'type' => 'set',
					'items' => $this->getRelationItems(),
					'default' => ['event'],
				],
				'raw_data' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.raw_data_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.raw_data_description',
					'type' => 'dropdown',
					'default' => 0,
					'options' => [
						0 => 'kurtjensen.mycalendar::lang.events_comp.opt_no',
						1 => 'kurtjensen.mycalendar::lang.events_comp.opt_yes',
					],
				],
				'descending' => [
					'title' => 'kurtjensen.mycalendar::lang.events_comp.descending_title',
					'description' => 'kurtjensen.mycalendar::lang.events_comp.descending_description',
					'type' => 'dropdown',
					'default' => 0,
					'options' => [
						0 => 'kurtjensen.mycalendar::lang.events_comp.opt_no',
						1 => 'kurtjensen.mycalendar::lang.events_comp.opt_yes',
					],
				],
			];

			return $properties;
			break;
		case 'week':
			$properties = [
				// Week
				'day' => [
					'title' => $this->langPath . 'com_prop_trait.day_title',
					'description' => $this->langPath . 'com_prop_trait.day_description',
					'default' => '{{ :day }}',
				],
			];
		case 'month':
			$properties = array_merge($properties,
				[
					// Month - Week
					'month' => [
						'title' => $this->langPath . 'com_prop_trait.month_title',
						'description' => $this->langPath . 'com_prop_trait.month_description',
						'default' => '{{ :month }}',
					],
					'year' => [
						'title' => $this->langPath . 'com_prop_trait.year_title',
						'description' => $this->langPath . 'com_prop_trait.year_description',
						'default' => '{{ :year }}',
					],
					'weekstart' => [
						'title' => $this->langPath . 'com_prop_trait.weekstart_title',
						'description' => $this->langPath . 'com_prop_trait.weekstart_description',
						'type' => 'dropdown',
						'default' => '0',
					],
					'dayprops' => [
						'title' => $this->langPath . 'com_prop_trait.dayprops_title',
						'description' => $this->langPath . 'com_prop_trait.dayprops_description',
					],
				]);
		case 'list':
			$properties = array_merge($properties,
				[
					// Month - Week - List
					'events' => [
						'title' => $this->langPath . 'com_prop_trait.events_title',
						'description' => $this->langPath . 'com_prop_trait.events_description',
					],
					'color' => [
						'title' => $this->langPath . 'com_prop_trait.color_title',
						'description' => $this->langPath . 'com_prop_trait.color_description',
						'type' => 'dropdown',
						'default' => 'red',
					],
					'loadstyle' => [
						'title' => $this->langPath . 'com_prop_trait.loadstyle_title',
						'description' => $this->langPath . 'com_prop_trait.loadstyle_description',
						'type' => 'dropdown',
						'default' => '1',
						'options' => [
							0 => $this->langPath . 'com_prop_trait.opt_no',
							1 => $this->langPath . 'com_prop_trait.opt_yes',
						],
					],
				]);
		}
		if (is_array($this->ComponentType)) {
			$this->EventsComp = new Events($this->ComponentType[1]);
			return array_merge($properties, $this->EventsComp->defineProperties());
		}
		return $properties;
	}

	public function init() {
		$type = is_array($this->ComponentType) ? $this->ComponentType[0] : $this->ComponentType;
		switch ($type) {
		case 'week':
		case 'month':
			$this->weekstart = $this->property('weekstart', 0);
		case 'list':
			if ($this->property('loadstyle')) {
				$this->addCss('/plugins/kurtjensen/mycalendar/assets/css/calendar.css');
			}
			if (is_array($this->ComponentType)) {
				$this->EventsComp->importProperties($this);
			}
			$this->color = $this->property('color');
		}
	}

	public function onRender() {
		$type = is_array($this->ComponentType) ? $this->ComponentType[0] : $this->ComponentType;
		switch ($type) {
		case 'week':
			// Must use onRender() for properties that can be modified in page
			$this->day = $this->property('day') ?: date('d');

		case 'month':
			$y_start = date('Y') - 2;
			$y_end = $y_start + 15;

			$this->month = in_array($this->property('month'), range(1, 12)) ? $this->property('month') : date('m');

			$this->year = in_array($this->property('year'), range($y_start, $y_end)) ? $this->property('year') : date('Y');

			$this->calHeadings = $this->getWeekstartOptions();

			$this->calcElements();

			$this->dayprops = $this->property('dayprops') ?: [];

		case 'list':

			$this->mergeEvents($this->property('events', []));
			//$this->events = $this->property('events') ?: [];
		}

	}

	public function calcElements() {
		$type = is_array($this->ComponentType) ? $this->ComponentType[0] : $this->ComponentType;
		switch ($type) {
		case 'week':
			$this->viewTime = new Carbon($this->month . '/' . $this->day . '/' . $this->year);
			$this->monthTitle = Lang::get('kurtjensen.mycalendar::lang.rrule.month.' . $this->viewTime->month); // Nov
			break;
		case 'month':
			$this->viewTime = new Carbon($this->month . '/1/' . $this->year); // 11/01/2016
			$this->monthTitle = Lang::get('kurtjensen.mycalendar::lang.rrule.month.' . $this->viewTime->month); // Nov
			break;
		}

		$this->monthNum = $this->viewTime->month;
		$this->running_day = $this->viewTime->dayOfWeek;
		$this->days_in_month = $this->viewTime->daysInMonth;

		switch ($type) {
		case 'week':
			$this->dayPointer = $this->day - $this->running_day - $this->weekstart - 1;
			break;
		case 'month':
			$this->dayPointer = $this->weekstart - $this->running_day; // 1 - 2 = -1
			break;
		}

		$prevMonthLastDay = $this->viewTime->copy()->subMonth()->daysInMonth;

		$this->prevMonthStartDay = $this->dayPointer + $prevMonthLastDay + 1;
		return $this->viewTime;
	}

	public function getMonthYear($offset) {
		$month = $this->month + $offset;
		$year = $this->year;
		if ($month == 13) {
			$year++;
			$month = 1;
		}
		if ($month == -1) {
			$year--;
			$month = 12;
		}
		return [$month, $year];
	}

	public function monthEvents($offset = 0) {
		list($month, $year) = $this->getMonthYear($offset);

		if (array_key_exists($year, $this->events)) {
			$yrEvents = $this->events[$year];
			if (array_key_exists($month, $yrEvents)) {
				return $yrEvents[$month];
			}
		}
		return [];
	}

	public function monthProperties($offset = 0) {
		list($month, $year) = $this->getMonthYear($offset);

		if (array_key_exists($year, $this->dayprops)) {
			$yrProps = $this->dayprops[$year];
			if (array_key_exists($month, $yrProps)) {
				return $yrProps[$month];
			}
		}
		return [];
	}

	public function trans($string) {
		return Lang::get($string);
	}

	public function getLinkpageOptions() {
		return ['' => Lang::get('kurtjensen.mycalendar::lang.events_comp.linkpage_opt_none')] +
		Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getRelationItems() {
		$occ = new Ocurrs();
		foreach ($occ->belongsTo as $key => $rel) {
			$options[$key] = $rel[0];
		}

		return $options;
	}

	public function getColorOptions() {
		$colors = [
			'red' => Lang::get($this->langPath . 'com_prop_trait.color_red'),
			'green' => Lang::get($this->langPath . 'com_prop_trait.color_green'),
			'blue' => Lang::get($this->langPath . 'com_prop_trait.color_blue'),
			'yellow' => Lang::get($this->langPath . 'com_prop_trait.color_yellow'),
		];
		return $colors;
	}

	public function getWeekstartOptions() {
		return [
			Lang::get($this->langPath . 'com_prop_trait.day_sun'),
			Lang::get($this->langPath . 'com_prop_trait.day_mon'),
			Lang::get($this->langPath . 'com_prop_trait.day_tue'),
			Lang::get($this->langPath . 'com_prop_trait.day_wed'),
			Lang::get($this->langPath . 'com_prop_trait.day_thu'),
			Lang::get($this->langPath . 'com_prop_trait.day_fri'),
			Lang::get($this->langPath . 'com_prop_trait.day_sat'),
		];
	}

	public function mergeEvents(array $new_events) {
		$events = $this->events;
		//foreach year
		foreach ($new_events as $year => $months) {
			//foreach month
			foreach ($months as $month => $days) {
				//foreach year
				foreach ($days as $day => $eventCollection) {
					foreach ($eventCollection as $event) {
						$events[$year][$month][$day][] = $event;
					}
				}
			}
		}
		$this->events = $events;
	}

	public function dataRequestProp($onOff = true) {
		return $this->data_request = $onOff;
	}

	public function setPrevLink($prev = ' ') {
		return $this->linkPrevious = $prev;
	}

	public function setNextLink($next = ' ') {
		return $this->linkNext = $next;
	}

	public function prevLink() {
		$type = is_array($this->ComponentType) ? $this->ComponentType[0] : $this->ComponentType;

		if (!$this->linkPrevious == null) {
			return $this->linkPrevious;
		}
		$monthParam = $this->paramName('month');
		$yearParam = $this->paramName('year');
		$dayParam = $this->paramName('day');
		if ($type == 'month') {
			$prevPeriod = $this->viewTime->copy()->subMonth();
		} else {
			$prevPeriod = $this->viewTime->copy()->subWeek();
		}
		return '
                <a href="' . $this->currentPageUrl([$yearParam => $prevPeriod->year, $monthParam => $prevPeriod->month, $dayParam => $prevPeriod->day]) . '">
                ' . $this->trans('kurtjensen.mycalendar::lang.com_prop_trait.previous') . '</a>';
	}

	public function nextLink() {
		$type = is_array($this->ComponentType) ? $this->ComponentType[0] : $this->ComponentType;

		if (!$this->linkNext == null) {
			return $this->linkNext;
		}
		$monthParam = $this->paramName('month');
		$yearParam = $this->paramName('year');
		$dayParam = $this->paramName('day');
		if ($type == 'month') {
			$nextPeriod = $this->viewTime->copy()->addMonth();
		} else {
			$nextPeriod = $this->viewTime->copy()->addWeek();
		}

		return '
                <a href="' . $this->currentPageUrl([$yearParam => $nextPeriod->year, $monthParam => $nextPeriod->month, $dayParam => $nextPeriod->day]) . '">
                ' . $this->trans('kurtjensen.mycalendar::lang.com_prop_trait.next') . '</a>';
	}
}