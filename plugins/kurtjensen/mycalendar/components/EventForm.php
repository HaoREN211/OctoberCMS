<?php namespace KurtJensen\MyCalendar\Components;

use Auth;
use Cms\Classes\ComponentBase;
use KurtJensen\MyCalendar\Classes\RRValidator;
use KurtJensen\MyCalendar\Models\Category as MyCalCategory;
use KurtJensen\MyCalendar\Models\Event as MyCalEvent;
use KurtJensen\MyCalendar\Models\Settings;
use Lang;
use \Recurr\Rule;

class EventForm extends ComponentBase {
	use \KurtJensen\MyCalendar\Traits\Series;
	use \KurtJensen\MyCalendar\Traits\RRuleWidget;
	use \System\Traits\ViewMaker;

	public $myevents; // array of objects Multiple events belonging to user
	public $myevent; // object One Event belonging to user
	public $categorylist; // form select for categories
	public $user; // Rainlab.User if logged in
	public $allowpublish; // tinyInt indicating if user is allowed to publish
	public $ckeditor; // tinyInt indicating if ckeditor should be used
	public $is_copy; // tinyInt indicating if the new event is a duplicate of an old one
	public $ajaxResponse = // array of strings used to create a flash style response to ajax events
	[
		'context' => 'default',
		'title' => '',
		'content' => '',
		'footer' => '',
	];

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.event_form.name',
			'description' => 'kurtjensen.mycalendar::lang.event_form.description',
		];
	}

	public function defineProperties() {
		return [
			'allowpublish' => [
				'title' => 'kurtjensen.mycalendar::lang.event_form.allow_pub_title',
				'description' => 'kurtjensen.mycalendar::lang.event_form.allow_pub_description',
				'type' => 'dropdown',
				'default' => '1',
				'options' => [
					0 => 'kurtjensen.mycalendar::lang.event_form.opt_no',
					1 => 'kurtjensen.mycalendar::lang.event_form.opt_yes',
				],
			],
			'ckeditor' => [
				'title' => 'kurtjensen.mycalendar::lang.event_form.ckeditor_title',
				'description' => 'kurtjensen.mycalendar::lang.event_form.ckeditor_description',
				'type' => 'dropdown',
				'default' => '1',
				'options' => [
					0 => 'kurtjensen.mycalendar::lang.event_form.opt_no',
					1 => 'kurtjensen.mycalendar::lang.event_form.opt_yes',
				],
			],
			'bootstrapCDN' => [
				'title' => 'kurtjensen.mycalendar::lang.event_form.bs_cdn_title',
				'description' => 'kurtjensen.mycalendar::lang.event_form.bs_cdn_description',
				'type' => 'dropdown',
				'default' => '1',
				'options' => [
					0 => 'kurtjensen.mycalendar::lang.event_form.opt_no',
					1 => 'kurtjensen.mycalendar::lang.event_form.opt_yes',
				],
			],
		];
	}

	/**
	 * Execute this each time component is created
	 *
	 * checks for logged in user and set a few properties
	 *
	 * @return null
	 */
	public function init() {
		$this->user = Auth::getUser();
		$this->allowpublish = $this->property('allowpublish');
		$this->ckeditor = $this->property('ckeditor');
	}

	public function onRun() {

		$this->addCss('https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.4.0/css/pikaday.min.css');
		$this->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js');
		$this->addJs('https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.4.0/pikaday.min.js');

		$this->addJs('/plugins/kurtjensen/mycalendar/assets/js/jquery-clockpicker.js');
		$this->addCss('/plugins/kurtjensen/mycalendar/assets/css/jquery-clockpicker.css');

		$this->addCss('/plugins/kurtjensen/mycalendar/assets/css/cal-form.css');

		if ($this->property('bootstrapCDN')) {
			$this->addJs(Settings::get('boostrap_cdn', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'));
		}

		if ($this->ckeditor) {
			$this->addJs('//cdn.ckeditor.com/4.5.4/standard/ckeditor.js');
		}

		$this->page['myevents'] = $this->loadEvents();
		//$this->onEventForm();
	}

	/**
	 * Short function for accessing Lang file strings
	 * @param  string $string string key for lang.php file
	 * @return string Translated string
	 */
	public function trans($string) {
		return Lang::get('kurtjensen.mycalendar::lang.' . $string);
	}

	/**
	 * Retrieve All events owned by this user from the DB
	 * @return array of Event objects or null if user not logged in
	 */
	protected function loadEvents() {
		if (!$this->user->id) {
			return null;
		}
		$this->myevents = MyCalEvent::where('user_id', '=', $this->user->id)->
			orderBy('date')->
			orderBy('time')->
			get();
		return $this->myevents;
	}

	/**
	 * Retrieve One Event owned by this user from the DB
	 * @return object Event object or null if user not logged in
	 */
	protected function getEvent() {
		if (!$this->user->id) {
			return null;
		}
		$eventId = post('id');
		if (!$eventId) {
			$this->myevent = new MyCalEvent();
			$this->myevent->user_id = $this->user->id;
		} else {
			$this->myevent = MyCalEvent::where('user_id', '=', $this->user->id)->find($eventId);
		}
		return $this->myevent;
	}

	/**
	 * Ajax handler to generates form and populates values if they exist yet
	 * @return null if event object does not exist
	 */
	protected function onEventForm() {
		$this->addJs('/plugins/kurtjensen/mycalendar/assets/js/scheduler.js');
		if (!$this->getEvent()) {
			return null;
		}

		$this->is_copy = $this->page['is_copy'] = post('copy');

		$cat = isset($this->myevent->categorys->first()->id) ? $this->myevent->categorys->first()->id : 0;
		$this->categorylist = $this->page['categorylist'] = MyCalCategory::selector(
			$cat,
			array('class' => 'form-control custom-select',
				'id' => 'Form-field-myevent-category_id')
		);

		$formValues = $this->page['formVals'] = array_merge($this->parseRrule($this->myevent->pattern), $this->myevent->toArray());
		$this->page['rcurForm'] = $this->makePartial(plugins_path() . '/kurtjensen/mycalendar/formwidgets/rrule/partials/rrule', ['f' => $formValues]);
	}

	/**
	 * Ajax handler to save an event from form
	 * triggers onRun to show list after delete
	 * @return array for a flash like error message if there is a problem with form validation
	 */
	public function onSave() {
		$dates = $this->processPost();
		if (!$dates) {
			return ['#ajaxResponse' => $this->renderPartial('@ajaxResponse', $this->ajaxResponse)];
		}
		$this->myevent->save();
		$this->onRun();
	}

	/**
	 * Ajax handler to delete an event
	 * triggers onRun to show list after delete
	 * @return null if event id or user does not exist
	 */
	protected function onDelete() {
		$eventId = post('id');

		if (!($eventId && $this->user)) {
			return null;
		}

		$this->myevent = MyCalEvent::where('user_id', $this->user->id)
			->find($eventId);

		$this->myevent->delete();

		$this->onRun();
	}

	/**
	 * Ajax handler to preview an event and it's recurrences
	 * @return array for a flash like error message if there is a problem with form validation or a modal showing event preview
	 */
	public function onPreviewRrule() {
		$dates = $this->processPost();
		if (!$dates) {
			return ['#EventDetail' => $this->renderPartial('@ajaxResponse', $this->ajaxResponse)];
		}
		$occurrences = [];
		foreach ($dates as $occurrence) {
			$occurrences[] = $occurrence->getStart()->format('M d Y H:i') . ' - ' . $occurrence->getEnd()->format('H:i');
		}
		return ['#EventDetail' => $this->renderPartial('@details', ['ev' => $this->myevent, 'occs' => $occurrences, 'context' => 'success']), '#ajaxResponse' => ''];
	}

	/**
	 * validate form values and process them to create complete event data
	 * that can be saved to the DB.
	 * @return array of recurrence start an end dates
	 */
	public function processPost() {
		$RValidator = new RRValidator();
		if (!$RValidator->valid(post())) {
			// Sets a warning message

			$this->ajaxResponse = array_merge($this->ajaxResponse, [
				'context' => 'danger',
				'title' => 'Form Validation Error:',
				'content' => '<ul><li>' . implode('</li><li>', $RValidator->messages->all()) . '</li></ul>',
				'footer' => '',
			]);
			return false;
		}

		$pattern = $this->getSaveValue('');

		$this->getEvent();

		$this->myevent->name = post('name');
		$this->myevent->text = post('text');
		$this->myevent->date = post('date');
		$this->myevent->time = post('time');
		$this->myevent->length = post('length');
		$this->myevent->pattern = $pattern;
		$this->myevent->categorys = [post('category_id')];
		if ($this->allowpublish) {
			$this->myevent->is_published = post('is_published');
		}

		$start_at = $this->myevent->carbon_time;

		list($lengthHour, $lengthMinute) = explode(':', $this->myevent->length);

		$end_at = $start_at->copy();
		$end_at->addMinutes($lengthMinute)->addHours($lengthHour);

		if (post('FREQ') == 'SERIES') {

			$dates = $this->seriesRule($pattern, $start_at, $end_at);

		} else {

			$rules = new \Recurr\Rule($pattern, $start_at, $end_at);
			$transformer = new \Recurr\Transformer\ArrayTransformer;
			$dates = $transformer->transform($rules);
		}
		return $dates;
	}
}
