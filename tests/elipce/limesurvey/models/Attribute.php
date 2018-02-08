<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Model;

/**
 * Attribute Model
 */
class Attribute extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * Possible data types
     */
    const TYPE_NUMERIC = 'numeric';
    const TYPE_TEXT = 'text';
    const TYPE_DATE = 'date';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'type' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'  => 'elipce.limesurvey::lang.backend.attributes.name_label',
        'type'  => 'elipce.limesurvey::lang.backend.attributes.type_label',
        'value' => 'elipce.limesurvey::lang.backend.attributes.value_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_attributes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'value',
        'type'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'participant' => ['Elipce\LimeSurvey\Models\Participant', 'key' => 'participant_id']
    ];

    /**
     * Called before the model is validated.
     */
    public function beforeValidate()
    {
        if (!empty($this->value) && $this->type !== self::TYPE_TEXT) {
            if ($this->type === self::TYPE_NUMERIC) {
                $this->rules['value'] = 'numeric';
                $this->casts['value'] = 'double';
            } elseif ($this->type === self::TYPE_DATE) {
                $this->rules['value'] = 'date';
            }
        }
    }
}