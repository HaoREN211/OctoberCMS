<?php namespace KurtJensen\MyCalendar\Components;

use Cms\Classes\ComponentBase;

class EvList extends ComponentBase {
	public $ComponentType = 'list';
	use \KurtJensen\MyCalendar\Traits\MyCalComponentTraits;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.evlist.name',
			'description' => 'kurtjensen.mycalendar::lang.evlist.description',
		];
	}
}
