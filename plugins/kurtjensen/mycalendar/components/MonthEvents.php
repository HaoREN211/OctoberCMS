<?php namespace KurtJensen\MyCalendar\Components;

use KurtJensen\MyCalendar\Components\Month;

class MonthEvents extends Month {
	public $ComponentType = ['month', 'MonthEvents'];
	public $EventsComp = null;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.month_events.name',
			'description' => 'kurtjensen.mycalendar::lang.month_events.description',
		];
	}

	public function onRun() {
		$this->mergeEvents($this->EventsComp->loadEvents());
	}

	public function onShowEvent() {
		return $this->page['ev'] = $this->EventsComp->onShowEvent();
	}

}
