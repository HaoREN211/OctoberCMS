<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Model;

/**
 * Participant Model
 */
class Participant extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Encryptable;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'fn'    => 'required',
        'sn'    => 'required',
        'email' => 'required|email',
        'uid'   => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'firstname' => 'elipce.limesurvey::lang.backend.participants.fn_label',
        'lastname'  => 'elipce.limesurvey::lang.backend.participants.sn_label',
        'email'     => 'elipce.limesurvey::lang.backend.participants.email_label',
        'uid'       => 'elipce.limesurvey::lang.backend.participants.uid_label'
    ];

    /**
     * @var array The array of custom error messages.
     */
    public $customMessages = [];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_participants';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'email',
        'fn',
        'sn',
        'uid',
        'token'
    ];

    /**
     * @var array Encryptable fields
     */
    protected $encryptable = ['token'];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'session' => ['Elipce\LimeSurvey\Models\Session', 'key' => 'session_id'],
        'role'    => ['Elipce\LimeSurvey\Models\Role', 'key' => 'role_id']
    ];
    public $hasMany = [
        'custom_attributes' => ['Elipce\LimeSurvey\Models\Attribute', 'key' => 'participant_id']
    ];
    public $belongsToMany = [
        'active_surveys' => [
            'Elipce\LimeSurvey\Models\Survey',
            'table'    => 'elipce_limesurvey_surveys_participants',
            'key'      => 'participant_id',
            'otherKey' => 'survey_id',
            'scope'    => 'active',
            'pivot'    => ['token', 'completed', 'reminder_sent']
        ],
        'surveys'        => [
            'Elipce\LimeSurvey\Models\Survey',
            'table'    => 'elipce_limesurvey_surveys_participants',
            'key'      => 'participant_id',
            'otherKey' => 'survey_id',
            'pivot'    => ['token', 'completed', 'reminder_sent']
        ]
    ];

    /**
     * Get roles options.
     *
     * @return array
     */
    public function getRoleOptions()
    {
        return $this->session->story->roles->lists('name', 'id');
    }

    /**
     * Mutator for full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->fn . ' ' . $this->sn;
    }
}