<?php namespace KurtJensen\MyCalendar\Components;

use Cms\Classes\ComponentBase;

class Week extends ComponentBase {
	public $ComponentType = 'week';
	use \KurtJensen\MyCalendar\Traits\MyCalComponentTraits;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.week.name',
			'description' => 'kurtjensen.mycalendar::lang.week.description',
		];
	}
}
