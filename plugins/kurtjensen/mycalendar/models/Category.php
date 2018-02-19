<?php namespace KurtJensen\MyCalendar\Models;

use Form;
use Model;
use System\Classes\PluginManager;

/**
 * Category Model
 */
class Category extends Model {
	use \October\Rain\Database\Traits\Validation;

	public $permOptions = [];

	public $table = 'kurtjensen_mycal_categories';

	/*
		     * Validation

		    name
		    slug
		    description
	*/
	public $rules = [
		'name' => 'required',
		'slug' => 'required|between:3,64|unique:kurtjensen_mycal_categories',
	];

	protected $guarded = [];

	public $belongsToMany = [
		'events' => ['KurtJensen\MyCalendar\Models\Event',
			'table' => 'kurtjensen_mycal_categorys_events',
			'key' => 'category_id',
			'otherKey' => 'event_id'],
	];

	/**
	 * @var array Cache for nameList() method
	 */
	protected static $nameList = [];

	public function beforeValidate() {
		// Generate a URL slug for this model
		if (!$this->exists && !$this->slug) {
			$this->slug = Str::slug($this->name);
		}

	}

	public function afterDelete() {
		$this->events()->detach();
	}

	public function getEventCountAttribute() {
		return $this->events()->count();
	}

	public static function getNameList($includeBlank = false) {
		if (count(self::$nameList)) {
			return self::$nameList;
		}

		$list = self::orderBy('name')->lists('name', 'id');
		if ($includeBlank) {
			$list = [0 => '- Select One -'] + $list;
		}

		return self::$nameList = $list;
	}

	public static function selector($selectedValue = null, $options = [], $name = 'category_id', $includeBlank = true) {
		return Form::select($name, self::getNameList($includeBlank), $selectedValue, $options);
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
}
