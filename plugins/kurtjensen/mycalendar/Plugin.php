<?php namespace KurtJensen\MyCalendar;

use KurtJensen\MyCalendar\Controllers\Events as EventController;
use KurtJensen\MyCalendar\Models\Category as CategoryModel;
use KurtJensen\MyCalendar\Models\Event as EventModel;
//use RainLab\User\Models\User;
use System\Classes\PluginBase;
use System\Classes\PluginManager;

/**
 * MyCalendar Plugin Information File
 */
class Plugin extends PluginBase {

	public function messageURL() {
		return 'http://firemankurt.com/notices/';
	}

	public function boot() {
		$manager = PluginManager::instance();
		if ($manager->exists('rainlab.user')) {
			\RainLab\User\Models\User::extend(function ($model) {
				$model->hasMany['mycalevents'] = [
					'KurtJensen\MyCalendar\Models\Events',
					'table' => 'kurtjensen_mycal_events'];
			});

			EventModel::extend(function ($model) {
				$model->belongsTo['user'] = [
					'RainLab\User\Models\User',
					'table' => 'user',
					'key' => 'user_id',
					'otherKey' => 'id'];
			});

			EventController::extendFormFields(function ($form, $model, $context) {

				if (!$model instanceof EventModel) {
					return;
				}

				$form->addFields([
					'user_id' => [
						'label' => 'kurtjensen.mycalendar::lang.event.user_id',
						'type' => 'dropdown',
						'span' => 'right',
					],
				]);
			});

			EventController::extendListColumns(function ($lists, $model) {
				$lists->addColumns([
					'fname' => [
						'label' => 'kurtjensen.mycalendar::lang.event.fname',
						'relation' => 'user',
						'select' => 'name',
						'searchable' => 'true',
						'sortable' => 'true',
					],
					'lname' => [
						'label' => 'kurtjensen.mycalendar::lang.event.lname',
						'relation' => 'user',
						'select' => 'surname',
						'searchable' => 'true',
						'sortable' => 'true',
					],
				]);
			});
		}

		if ($manager->exists('kurtjensen.passage')) {
			CategoryModel::extend(function ($model) {
				$model->belongsTo['permission'] = ['KurtJensen\Passage\Models\Key',
					'table' => 'kurtjensen_passage_keys',
					'key' => 'permission_id'];
			});

		}

	}

	public function registerComponents() {
		return [
			//'KurtJensen\MyCalendar\Components\Test' => 'Test',
			'KurtJensen\MyCalendar\Components\Month' => 'Month',
			'KurtJensen\MyCalendar\Components\MonthEvents' => 'MonthEvents',
			'KurtJensen\MyCalendar\Components\Week' => 'Week',
			'KurtJensen\MyCalendar\Components\WeekEvents' => 'WeekEvents',
			'KurtJensen\MyCalendar\Components\EvList' => 'EvList',
			'KurtJensen\MyCalendar\Components\ListEvents' => 'ListEvents',
			'KurtJensen\MyCalendar\Components\Events' => 'Events',
			'KurtJensen\MyCalendar\Components\Event' => 'Event',
			'KurtJensen\MyCalendar\Components\EventForm' => 'EventForm',
		];
	}

	public function registerSettings() {
		return [
			'settings' => [
				'label' => 'kurtjensen.mycalendar::lang.plugin.name',
				'icon' => 'icon-birthday-cake',
				'description' => 'kurtjensen.mycalendar::lang.settings.description',
				'class' => 'KurtJensen\MyCalendar\Models\Settings',
				'order' => 199,
				'permissions' => ['kurtjensen.mycalendar.settings'],
			],
		];

	}

	public function registerFormWidgets() {
		return [
			'KurtJensen\MyCalendar\FormWidgets\RRule' => [
				'label' => 'Recurrence Rule Editor',
				'code' => 'rrule',
			],
		];
	}

}
