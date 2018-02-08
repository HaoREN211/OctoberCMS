<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use Carbon\Carbon;

/**
 * Survey Model
 */
class Survey extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'duration' => 'required:update|numeric|min:1'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'duration' => 'elipce.limesurvey::lang.backend.surveys.duration_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_surveys';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'duration'    => 'int',
        'external_id' => 'int',
        'active'      => 'boolean'
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'start_days',
        'end_days',
        'duration',
        'active',
        'external_id'
    ];

    /**
     * @var array Date fields
     */
    protected $dates = [
        'start_date',
        'end_date'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'session'  => ['Elipce\LimeSurvey\Models\Session', 'key' => 'session_id'],
        'role'     => ['Elipce\LimeSurvey\Models\Role', 'key' => 'role_id'],
        'template' => ['Elipce\LimeSurvey\Models\Template', 'key' => 'template_id']
    ];
    public $belongsToMany = [
        'participants'       => [
            'Elipce\LimeSurvey\Models\Participant',
            'table'    => 'elipce_limesurvey_surveys_participants',
            'key'      => 'survey_id',
            'otherKey' => 'participant_id',
            'pivot'    => ['token', 'completed', 'reminder_sent']
        ],
        'participants_count' => [
            'Elipce\LimeSurvey\Models\Participant',
            'table'    => 'elipce_limesurvey_surveys_participants',
            'key'      => 'survey_id',
            'otherKey' => 'participant_id',
            'count'    => true
        ],
        'links'              => [
            'Elipce\LimeSurvey\Models\Survey',
            'table'    => 'elipce_limesurvey_links',
            'key'      => 'survey_id',
            'otherKey' => 'linked_survey_id',
            'pivot'    => ['question', 'answer']
        ]
    ];

    /**
     * Mutator for slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->session->slug . '_' . str_slug($this->name, '_');
    }

    /**
     * Scope a query to only include active surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('active', true);
    }

    /**
     * Scope a query to only include pending and incoming surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('end_date', '>=', $now)
            ->where('active', false);
    }

    /**
     * Scope a query to only include pending surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('start_date', '>=', $now)
            ->where('active', false);
    }

    /**
     * Scope a query to only include incoming surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIncoming($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('start_date', '<=', $now)
            ->where('active', false);
    }

    /**
     * Scope a query to only include expired surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpired($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('end_date', '<', $now)
            ->where('active', true);
    }

    /**
     * Scope a query to only include archived surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope a query to only include deletable surveys.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDeletable($query)
    {
        return $query->where('is_archived', true)->orWhere('active', false);
    }

    /**
     * Before the existing model is saved.
     */
    public function beforeSave()
    {
        $this->calculateDates();
    }

    /**
     * Calculate survey dates belongs to session dates.
     */
    public function calculateDates()
    {
        $this->calculateStartDate();
        $this->calculateEndDate();
    }

    /**
     * Calculate survey start date belongs to session dates.
     */
    protected function calculateStartDate()
    {
        if ($this->start_days !== null) {

            $this->start_date = $this->session->start_date
                ->addDays($this->start_days);

        } else {

            $this->start_date = $this->session->end_date
                ->addDays($this->end_days);
        }
    }

    /**
     * Calculate survey end date belongs to survey start date and its duration.
     */
    protected function calculateEndDate()
    {
        $this->end_date = $this->start_date->addDays($this->duration);
    }

    /**
     * Update defined field dependencies.
     *
     * @param $fields
     * @param null $context
     */
    public function filterFields($fields, $context = null)
    {
        if ($context == 'update') {
            /*
             * If survey is expired, disable duration field
             */
            if ($this->end_date->isPast()) {
                $fields->duration->disabled = true;
            } /*
             * Calculate end date from survey start date + duration
             */
            else {
                $this->end_date = $this->start_date
                    ->addDays($fields->duration->value);
            }
        }
    }
}