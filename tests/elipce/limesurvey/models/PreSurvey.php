<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Model;

/**
 * PreSurvey Model
 */
class PreSurvey extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Purgeable;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'       => 'required',
        'duration'   => 'required|numeric|min:1',
        'start_days' => 'required_without:end_days|numeric',
        'end_days'   => 'required_without:start_days|numeric',
        'roles'      => 'required',
        'template'   => 'required'
    ];

    /**
     * @var array Attribute names for validation errors.
     */
    public $attributeNames = [
        'name'       => 'elipce.limesurvey::lang.backend.presurveys.name_label',
        'duration'   => 'elipce.limesurvey::lang.backend.presurveys.duration_label',
        'start_days' => 'elipce.limesurvey::lang.backend.presurveys.days_label',
        'end_days'   => 'elipce.limesurvey::lang.backend.presurveys.days_label',
        'roles'      => 'elipce.limesurvey::lang.backend.presurveys.roles_label',
        'template'   => 'elipce.limesurvey::lang.backend.presurveys.template_label'
    ];

    /**
     * @var array List of attributes to purge.
     */
    protected $purgeable = [
        'days',
        'when'
    ];

    /**
     * When dropdown options.
     */
    const AFTER_START_OPTION = 'after_start';
    const BEFORE_START_OPTION = 'before_start';
    const AFTER_END_OPTION = 'after_end';
    const BEFORE_END_OPTION = 'before_end';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_presurveys';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'start_days',
        'end_days',
        'duration'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'story'    => ['Elipce\LimeSurvey\Models\Story', 'key' => 'story_id'],
        'template' => ['Elipce\LimeSurvey\Models\Template', 'key' => 'template_id']
    ];
    public $belongsToMany = [
        'roles'       => [
            'Elipce\LimeSurvey\Models\Role',
            'table'    => 'elipce_limesurvey_presurveys_roles',
            'key'      => 'presurvey_id',
            'otherKey' => 'role_id'
        ],
        'roles_count' => [
            'Elipce\LimeSurvey\Models\Role',
            'table'    => 'elipce_limesurvey_presurveys_roles',
            'key'      => 'presurvey_id',
            'otherKey' => 'role_id',
            'count'    => true
        ]
    ];

    /**
     * Mutator for days attribute.
     *
     * @return number
     */
    public function getDaysAttribute()
    {
        if ($this->start_days !== null) {

            return abs($this->start_days);

        } elseif ($this->end_days !== null) {

            return abs($this->end_days);

        } else {
            return null;
        }
    }

    /**
     * Mutator for when attribute.
     *
     * @return string
     */
    public function getWhenAttribute()
    {
        if ($this->start_days !== null) {

            if ($this->start_days > 0) {
                return self::AFTER_START_OPTION;
            } else {
                return self::BEFORE_START_OPTION;
            }
        } elseif ($this->end_days !== null) {

            if ($this->end_days > 0) {
                return self::AFTER_START_OPTION;
            } else {
                return self::BEFORE_END_OPTION;
            }
        } else {

            return null;
        }
    }

    /**
     * Filter form fields, used by FormController behavior.
     *
     * @param $fields
     * @param null $context
     */
    public function filterFields($fields, $context = null)
    {
        if ($fields->days->value !== null) {

            switch ($fields->when->value) {
                case self::AFTER_START_OPTION:
                    $fields->start_days->value = (int) $fields->days->value;
                    $fields->end_days->value = null;
                    break;
                case self::BEFORE_START_OPTION:
                    $fields->start_days->value = -(int) $fields->days->value;
                    $fields->end_days->value = null;
                    break;
                case self::AFTER_END_OPTION:
                    $fields->end_days->value = (int) $fields->days->value;
                    $fields->start_days->value = null;
                    break;
                case self::BEFORE_END_OPTION:
                    $fields->end_days->value = -(int) $fields->days->value;
                    $fields->start_days->value = null;
                    break;
                default:
                    $fields->start_days->value = null;
                    $fields->end_days->value = null;
                    break;
            }
        }
    }
}