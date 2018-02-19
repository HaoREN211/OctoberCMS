<?php namespace KurtJensen\MyCalendar\Models;

use Model;

/**
 * Occurence Model
 */
class Occurrence extends Model {
	use \October\Rain\Database\Traits\SoftDeleting;

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'kurtjensen_mycalendar_occurrences';

	/**
	 * @var array Guarded fields
	 */
	protected $guarded = [];

	/**
	 * @var array Fillable fields
	 */
	protected $fillable = [
		'event_id',
		'relation',
		'relation_id',
		'start_at',
		'end_at',
		//'is_modified',
		'is_allday',
		//'is_canceled',
	];
	protected $primaryKey = 'id';

	public $exists = false;

	public $timestamps = true;

	protected $dates = [
		'start_at',
		'end_at',
		'deleted_at',
	];

	/**
	 * @var array Relations
	 */
	public $belongsTo = [
		'event' => ['KurtJensen\MyCalendar\Models\Event',
			'table' => 'kurtjensen_mycal_events',
		],
	];

}
