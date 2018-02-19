<?php namespace KurtJensen\MyCalendar\Components;

use KurtJensen\MyCalendar\Components\Week;

class WeekEvents extends Week {
	public $ComponentType = ['week', 'WeekEvents'];
	public $EventsComp = null;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.week_events.name',
			'description' => 'kurtjensen.mycalendar::lang.week_events.description',
		];
	}
	public function onRun() {
		$this->mergeEvents($this->EventsComp->loadEvents());
	}

	public function onShowEvent() {
		return $this->page['ev'] = $this->EventsComp->onShowEvent();
	}

}
