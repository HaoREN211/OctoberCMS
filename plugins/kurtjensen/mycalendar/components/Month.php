<?php namespace KurtJensen\MyCalendar\Components;

use Cms\Classes\ComponentBase;

class Month extends ComponentBase {
	public $ComponentType = 'month';

	use \KurtJensen\MyCalendar\Traits\MyCalComponentTraits;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.month.name',
			'description' => 'kurtjensen.mycalendar::lang.month.description',
		];
	}
}
