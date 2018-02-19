<?php namespace KurtJensen\MyCalendar\Models;

use Carbon\Carbon;
use Html;
use KurtJensen\MyCalendar\Models\Settings;
use Model;
//use RainLab\User\Models\User as UserModel;
use System\Classes\PluginManager;
use \Recurr\Rule;
use \Recurr\Transformer\ArrayTransformer;

/**
 * event Model
 */
class Event extends Model {
	use \KurtJensen\MyCalendar\Traits\Series;
	use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Purgeable;

	/**
	 * @var array List of attributes to purge.
	 */
	protected $purgeable = ['custom_pattern'];

	/**
	 * @var array Permissions cache.
	 */
	public $permarray = [];

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'kurtjensen_mycal_events';

	/**
	 * Validation rules
	 */
	public $rules = [
		'date' => 'date|required',
		'name' => 'required',
	];

	/**
	 * @var array Guarded fields
	 */
	protected $guarded = [];

	/**
	 * @var array Fillable fields
	 */
	protected $fillable = [
		'name',
		'is_published',
		'user_id',
		'date',
		'time',
		'text',
		'link',
		'length',
		'pattern',
		'categorys',
	];
	protected $primaryKey = 'id';

	public $exists = false;

	protected $dates = ['date'];

	public $timestamps = true;

	/**
	 * @var array Relations
	 */
	public $belongsToMany = [
		'categorys' => ['KurtJensen\MyCalendar\Models\Category',
			'table' => 'kurtjensen_mycal_categorys_events',
			'key' => 'event_id',
			'otherKey' => 'category_id',
		],
	];
	public $hasMany = [
		'occurrences' => ['KurtJensen\MyCalendar\Models\Occurrence',
			'table' => 'kurtjensen_mycalendar_occurrences',
			'key' => 'event_id',
			'conditions' => 'relation = \'event\'',
			'delete' => true,
		],
	];

	public $attributes = [
		'day' => '',
		'month' => '',
		'year' => '',
		'human_time' => '',
		'carbon_time' => '',
		'owner_name' => '',
	];
	public function __construct(array $attributes = array()) {
		$settings = Settings::instance();
		$this->setRawAttributes([
			'date' => new Carbon(),
			'pattern' => 'FREQ=DAILY;INTERVAL=1;COUNT=1;',
			'length' => '01:00',
			'time' => '12:00'], true);

		parent::__construct($attributes);
	}
	/*
		    public function getDateAttribute($value)
		    {
		    return $value ? $value : new Carbon();
		    }
	*/
	public function getDayAttribute($value) {
		//die(get_class($this->date));
		return $this->date->day;
	}

	public function getMonthAttribute() {
		return $this->date->month;
	}

	public function getYearAttribute() {
		return $this->date->year;
	}

	public function getHumanTimeAttribute() {
		$time = isset($this->time) ? $this->carbon_time->format(Settings::get('time_format', 'g:i a')) : '';
		return $time;
	}

	public function getCarbonTimeAttribute() {
		if (!$this->time) {
			return '';
		}
		$time = new Carbon($this->time);
		$date = $this->date->copy();
		$date->hour = $time->hour;
		$date->minute = $time->minute;
		$date->second = 0;
		return $date;
	}

	public function getOwnerNameAttribute() {
		$manager = PluginManager::instance();
		if ($manager->exists('rainlab.user')) {
			if ($this->user) {
				return $this->user->name . ' ' . $this->user->surname;
			}
		}
		return '';
	}

	public function getPatternAttribute($value) {
		return $value ? $value : 'FREQ=DAILY;INTERVAL=1;COUNT=1;';
	}

	public function beforeSave() {

		$this->name = Html::clean($this->name);
		$this->text = Html::clean($this->text);

		unset(
			$this->attributes['human_time'],
			$this->attributes['owner_name'],
			$this->attributes['carbon_time']);
	}

	public function beforeDelete() {
		Occurrence::where('event_id', '=', $this->id)->
			where('relation', '=', 'events')->forceDelete();

	}

	public function getDayOptions($month) {
		if ($this->month && $this->year) {
			$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
			$days = range(1, $daysInMonth);
			return array_combine($days, $days);
		}
		return [0 => 'Pick a Month AND Year'];
	}

	public function getMonthOptions() {
		$months = ['0', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		unset($months[0]);
		return $months;
	}

	public function getYearOptions() {
		$year = date('Y');
		$years = range($year, $year + 5);
		return array_combine($years, $years);
	}

	public function getUserIdOptions($keyValue = null) {
		$Users = [];
		$manager = PluginManager::instance();
		if ($manager->exists('rainlab.user')) {
			foreach (\RainLab\User\Models\User::orderBy('surname')->
				orderBy('name')->get() as $user) {
				$Users[$user->id] = $user->surname . ', ' . $user->name;
			}

			return $Users;
		}
		return [0 => 'Rainlab User Model Not Installed'];
	}

	public function scopePublished($query) {
		return $query->where('is_published', true);
	}

	/**
	 * Restricts to dates after $days days before today.
	 * @param  object $query
	 * @param  integer $days
	 * @return object $query
	 */
	public function scopePast($query, $days) {
		$date = new Carbon();
		$date->subDays($days);
		return $query->where('date', '>=', $date);
	}

	/**
	 * Restricts to dates after $days days from today.
	 * @param  object $query
	 * @param  integer $days
	 * @return object $query
	 */
	public function scopeFuture($query, $days) {
		$date = new Carbon();
		$date->addDays($days);
		return $query->where('date', '<=', $date);
	}

	public function scopeWithOwner($query) {
		$manager = PluginManager::instance();
		if ($manager->exists('rainlab.user')) {
			return $query->with('user');
		}
		return $query;
	}

	public function scopePermisions($query, $user_id, $public_perm = [], $deny_perm = 0) {
		$manager = PluginManager::instance();

		if ($manager->exists('kurtjensen.passage')) {

			$akeys = array_keys(app('PassageService')::passageKeys());
			if ($user_id) {
				$permarray = array_merge($akeys, $public_perm);
			} else {
				$permarray = $public_perm;
			}

			$permarray = array_unique($permarray);

			$query->whereHas('categorys', function ($q) use ($permarray) {
				$q->whereIn('permission_id', $permarray);
			})
				->whereDoesntHave('categorys', function ($q) use ($deny_perm) {
					$q->where('permission_id', $deny_perm);
				});
			return $query;
		}

		return $query;
	}

	public function scopeDuring($query, $start, $end) {
		$query->where('start_at', '<', $end)->
			where('end_at', '>=', $start);
		return $query;
	}

	public function afterCreate() {
		$this->fillOccurrences($this->generateOccurranceDates());
	}

	public function generateOccurranceDates() {
		$start_at = $this->carbon_time;

		$length = new Carbon($this->length);

		$end_at = $start_at->copy();
		$end_at->addMinutes($length->minute)->addHours($length->hour);

		$pieces = explode(';', $this->pattern);

		if (in_array('FREQ=SERIES', $pieces)) {

			$dates = $this->seriesRule($this->pattern, $start_at, $end_at);

		} else {

			$rules = new \Recurr\Rule($this->pattern, $start_at, $end_at);
			$transformer = new \Recurr\Transformer\ArrayTransformer;
			$dates = $transformer->transform($rules);
		}

		return $dates;
	}

	public function fillOccurrences($dates) {
		foreach ($dates as $recurrence) {
			$occurrences[] = new Occurrence([
				'event_id' => $this->id,
				'relation' => 'events',
				'relation_id' => $this->id,
				'start_at' => $recurrence->getStart()->format('Y-m-d H:i:s'),
				'end_at' => $recurrence->getEnd()->format('Y-m-d H:i:s'),
				'is_modified' => 0,
				'is_allday' => 0,
				'is_canceled' => 0,
			]);
		}
		$this->occurrences()->saveMany($occurrences);
	}

	public function afterUpdate() {
		// Check if any change will cause a change in recurrence.
		if (
			$this->date !== $this->getOriginal('date') &&
			$this->time !== $this->getOriginal('time') &&
			$this->length !== $this->getOriginal('length') &&
			$this->pattern !== $this->getOriginal('pattern')
		) {
			return;
		}

		$this->updateOccurrences($this->generateOccurranceDates());

	}

	public function updateOccurrence($occurrence, $recurrence) {
		if (!$recurrence) {
			return $occurrence->delete();
		}
		$occurrence->deleted_at = null;
		$occurrence->event_id = $this->id;
		$occurrence->relation = 'events';
		$occurrence->relation_id = $this->id;
		$occurrence->start_at = $recurrence->getStart()->format('Y-m-d H:i:s');
		$occurrence->end_at = $recurrence->getEnd()->format('Y-m-d H:i:s');
		$occurrence->is_modified = 1;
		$occurrence->is_allday = 0;
		$occurrence->is_canceled = 0;
		$occurrence->save();
	}

	public function updateOccurrences($dates) {
		$occurrences = Occurrence::where('event_id', $this->id)->orderBy('start_at')->withTrashed()->get();
		$countNew = count($dates);
		$countOld = $occurrences->count();
		$i = 0;
		if ($countOld >= $countNew) {
			// More Old than New
			foreach ($occurrences as $occurrence) {
				if ($countNew > $i) {
					$recurrence = $dates[$i++];
				} else {
					$recurrence = false;
				}
				$this->updateOccurrence($occurrence, $recurrence);
			}

		} else {
			// More New than Old
			foreach ($dates as $recurrence) {
				if ($countOld > $i) {
					$occurrence = $occurrences[$i++];
				} else {
					$occurrence = new Occurrence();
				}
				$this->updateOccurrence($occurrence, $recurrence);
			}
		}
	}
}
