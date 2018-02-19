<?php namespace KurtJensen\MyCalendar\Models;

use Model;

/**
 * CategorysEvents Model
 */
class CategorysEvents extends Model {

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'kurtjensen_mycal_categorys_events';

	/**
	 * @var array Guarded fields
	 */
	protected $guarded = [];

	/**
	 * @var array Fillable fields
	 */
	protected $fillable = ['*'];

	/**
	 * @var array Relations
	 */
	public $hasOne = [
		'category' => ['KurtJensen\MyCalendar\Models\Category',
			'table' => 'kurtjensen_mycal_category',
		],
		'event' => ['KurtJensen\MyCalendar\Models\Event',
			'table' => 'kurtjensen_mycal_event',
		],
	];

}