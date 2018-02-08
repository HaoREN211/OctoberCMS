<?php namespace Elipce\LimeSurvey\Models;

use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use Carbon\Carbon;

/**
 * Session Model
 */
class Session extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Purgeable;
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * Default Excel columns
     */
    const DEFAULT_COLUMNS = [
        'id',
        'email',
        'nom',
        'prenom',
        'role'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'         => 'required',
        'story'        => 'required',
        'start_date'   => 'required|date|before:end_date|after:yesterday',
        'end_date'     => 'required|date',
        'uid_column'   => 'required:create',
        'email_column' => 'required:create',
        'fn_column'    => 'required:create',
        'sn_column'    => 'required:create',
        'role_column'  => 'required:create',
        'portal'       => 'required:create'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'         => 'elipce.limesurvey::lang.backend.sessions.name_label',
        'story'        => 'elipce.limesurvey::lang.backend.sessions.story_label',
        'start_date'   => 'elipce.limesurvey::lang.backend.sessions.start_date_label',
        'end_date'     => 'elipce.limesurvey::lang.backend.sessions.end_date_label',
        'uid_column'   => 'elipce.limesurvey::lang.backend.sessions.uid_column_label',
        'email_column' => 'elipce.limesurvey::lang.backend.sessions.email_column_label',
        'fn_column'    => 'elipce.limesurvey::lang.backend.sessions.sn_column_label',
        'sn_column'    => 'elipce.limesurvey::lang.backend.sessions.sn_column_label',
        'role_column'  => 'elipce.limesurvey::lang.backend.sessions.role_column_label',
        'portal'       => 'elipce.limesurvey::lang.backend.sessions.portal_label'
    ];

    /**
     * @var array The array of custom error messages.
     */
    public $customMessages = [
        'start_date.after'  => 'elipce.limesurvey::lang.backend.sessions.start_date_validation_after',
        'start_date.before' => 'elipce.limesurvey::lang.backend.sessions.start_date_validation_before'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_sessions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'uid_column',
        'email_column',
        'fn_column',
        'sn_column',
        'role_column',
        'created_by'
    ];

    /**
     * @var array Purgeable fields
     */
    protected $purgeable = [
        'custom_columns'
    ];

    /**
     * @var array Date fields
     */
    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'surveys'             => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id'],
        'participants'        => ['Elipce\LimeSurvey\Models\Participant', 'key' => 'session_id'],
        'participants_count'  => ['Elipce\LimeSurvey\Models\Participant', 'key' => 'session_id', 'count' => true],
        'inactive_surveys'    => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id', 'scope' => 'inactive'],
        'active_surveys'      => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id', 'scope' => 'active'],
        'incoming_surveys'    => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id', 'scope' => 'incoming'],
        'pending_surveys'     => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id', 'scope' => 'pending'],
        'expired_surveys'     => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id', 'scope' => 'expired'],
        'archived_surveys'    => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'session_id', 'scope' => 'archived'],
        'story_columns'       => ['Elipce\LimeSurvey\Models\Column', 'key' => 'story_id', 'otherKey' => 'story_id', 'scope' => 'mask'],
        'session_columns'     => ['Elipce\LimeSurvey\Models\Column', 'key' => 'session_id']
    ];
    public $belongsTo = [
        'story'  => ['Elipce\LimeSurvey\Models\Story', 'key' => 'story_id'],
        'portal' => ['Elipce\Multisite\Models\Portal', 'key' => 'portal_id'],
        'author' => ['Backend\Models\User', 'key' => 'created_by']
    ];
    public $attachOne = [
        'import_file' => 'System\Models\File'
    ];

    /**
     * Mutator for slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        $slugs = [];

        if ($this->author) {
            $slugs[] = str_slug($this->author->last_name, '_');
        }

        $slugs[] = str_slug($this->story->name, '_');
        $slugs[] = str_slug($this->name, '_');

        return implode('_', $slugs);
    }

    /**
     * Mutator for evaluation start date attribute.
     */
    public function getEvaluationStartDateAttribute()
    {
        $firstSurvey = $this->surveys->sortBy('start_date')->first();

        return $firstSurvey->start_date;
    }

    /**
     * Mutator for evaluation end date attribute.
     */
    public function getEvaluationEndDateAttribute()
    {
        $lastSurvey = $this->surveys->sortByDesc('end_date')->first();

        return $lastSurvey->end_date;
    }

    /**
     * Scope a query to only include active sessions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now);
    }

    /**
     * Scope a query to only include pending sessions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('start_date', '>', $now);
    }

    /**
     * Scope a query to only include expired sessions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeExpired($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('end_date', '<', $now);
    }

    /**
     * Scope a query to only include session
     * for a given portal.
     *
     * @param Builder $query
     * @param int $portal
     *
     * @return Builder
     */
    public function scopeApplyPortal($query, $portal)
    {
        return $query->where('portal_id', $portal);
    }

    /**
     * Scope a query to only include session
     * for a given story.
     *
     * @param Builder $query
     * @param int $story
     *
     * @return Builder
     */
    public function scopeApplyStory($query, $story)
    {
        return $query->where('story_id', $story);
    }

    /**
     * Scope a query to only include isAllowed sessions
     * for a given backend user.
     *
     * @param Builder $query
     * @param BackendUser $user
     *
     * @return Builder
     */
    public function scopeIsAllowed($query, $user)
    {
        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->where('portal_id', $user->portal->id);
    }
}