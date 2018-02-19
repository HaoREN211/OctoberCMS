<?php namespace KurtJensen\MyCalendar\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use KurtJensen\MyCalendar\Models\Event as MyEvents;

/**
 * Occurrences Back-end Controller
 */
class Occurrences extends Controller
{
    public $events;
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KurtJensen.MyCalendar', 'mycalendar', 'occurrences');
        $this->events = MyEvents::where('pattern', '')->count();

    }

    public function onOpenSave()
    {
        $events = MyEvents::where('pattern', '')->limit(20)->get();
        foreach ($events as $event) {
            $event->pattern = 'FREQ=DAILY;INTERVAL=1;COUNT=1;';
            $event->length = $event->length ? $event->length : '01:00:00';
            $event->time = $event->time ? $event->time : '00:00:00';
            $event->save();
        }

        return ['#occ-alert' => $this->makePartial('rr_update', ['count' => MyEvents::where('pattern', '')->count()])];
    }
}
