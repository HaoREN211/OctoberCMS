<?php namespace KurtJensen\MyCalendar\Models;

use Model;
use System\Classes\PluginManager;

/**
 * Settings Model
 */
class Settings extends Model {
	use \October\Rain\Database\Traits\Validation;

	public $implement = ['System.Behaviors.SettingsModel'];

	public $settingsCode = 'kurtjensen_calendar_settings';

	public $settingsFields = 'fields.yaml';

	public $permOptions = [];

	/**
	 * Validation rules
	 */
	public $rules = [
		'public_perm' => 'required',
		'deny_perm' => 'required',
		'default_perm' => 'required',
	];

	public function initSettingsData() {
		$this->date_format = 'F jS, Y';
		$this->time_format = 'g:i a';
		$options = array_flip($this->getDropdownOptions());
		$this->public_perm = $this->public_perm ?: array_get($options, 'calendar_public', 0);
		$this->deny_perm = $this->deny_perm ?: array_get($options, 'calendar_deny_all', 0);
		$this->default_perm = $this->default_perm ?: array_get($options, 'calendar_deny_all', 0);
		$this->day_start = $this->day_start ?: '00:00:00';
		$this->day_end = $this->day_end ?: '23:59:00';
		$this->default_length = $this->default_length ?: '01:00:00';
		$this->max_count = $this->max_count ?: 365;
		$this->boostrap_cdn = $this->boostrap_cdn ?: 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js';
	}

	public function getDropdownOptions($fieldName = null, $keyValue = null) {
		if (count($this->permOptions)) {
			return $this->permOptions;
		}

		$manager = PluginManager::instance();
		if ($manager->exists('kurtjensen.passage')) {
			$this->permOptions = \KurtJensen\Passage\Models\Key::lists('name', 'id');
		}
		return $this->permOptions;
	}

	public function getDayStartAttribute($value) {
		$time = strtotime($value ? $value : '00:00:00');
		return date('H:i', $time);
	}

	public function getDayEndAttribute($value) {
		$time = strtotime($value ? $value : '23:59:00');
		return date('H:i', $time);
	}

	public function getDefaultLengthAttribute($value) {
		$time = strtotime($value ? $value : '01:00:00');
		return date('H:i', $time);
	}
}
