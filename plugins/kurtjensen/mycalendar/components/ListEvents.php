<?php namespace KurtJensen\MyCalendar\Components;

use KurtJensen\MyCalendar\Components\EvList;

class ListEvents extends EvList {
	public $ComponentType = ['list', 'ListEvents'];
	public $EventsComp = null;

	public function componentDetails() {
		return [
			'name' => 'kurtjensen.mycalendar::lang.list_events.name',
			'description' => 'kurtjensen.mycalendar::lang.list_events.description',
		];
	}

	public function onRun() {
		$this->mergeEvents($this->EventsComp->loadEvents());
	}

	public function onShowEvent() {
		$this->EventsComp->compLink = 'ListEvents';
		return $this->page['ev'] = $this->EventsComp->onShowEvent();
	}
}
