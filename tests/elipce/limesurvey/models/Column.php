<?php namespace Elipce\LimeSurvey\Models;

use Illuminate\Support\Facades\Lang;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Column Model
 */
class Column extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Nullable;

    /**
     * Required field names
     */
    const REQUIRED_FIELDS = [
        'uid_column'   => 'elipce.limesurvey::lang.backend.participants.uid_label',
        'email_column' => 'elipce.limesurvey::lang.backend.participants.email_label',
        'fn_column'    => 'elipce.limesurvey::lang.backend.participants.fn_label',
        'sn_column'    => 'elipce.limesurvey::lang.backend.participants.sn_label',
        'role_column'  => 'elipce.limesurvey::lang.backend.participants.role_label'
    ];

    /**
     * Possible data types
     */
    const TYPE_TEXT = 'text';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_DATE = 'date';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'   => 'required',
        'type'   => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.limesurvey::lang.backend.columns.name_label',
        'type' => 'elipce.limesurvey::lang.backend.columns.type_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_columns';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type',
        'field'
    ];

    /**
     * @var array Nullable attributes
     */
    protected $nullable = ['field'];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'story'   => ['Elipce\LimeSurvey\Models\Story', 'key' => 'story_id'],
        'session' => ['Elipce\LimeSurvey\Models\Session', 'key' => 'session_id']
    ];

    /**
     * Scope a query to only include mask columns.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeMask($query)
    {
        return $query->whereNull('field');
    }

    /**
     * Scope a query to only include required columns.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRequired($query)
    {
        return $query->whereNotNull('field');
    }

    /**
     * Mutator for slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return str_slug($this->name, '');
    }

    /**
     * Mutator for translated type attribute.
     *
     * @return string
     */
    public function getTranslatedTypeAttribute()
    {
        if (empty($this->type)) {
            return '<>';
        }

        $lang = [
            self::TYPE_TEXT    => Lang::get('elipce.limesurvey::lang.backend.columns.text_option'),
            self::TYPE_NUMERIC => Lang::get('elipce.limesurvey::lang.backend.columns.numeric_option'),
            self::TYPE_DATE    => Lang::get('elipce.limesurvey::lang.backend.columns.date_option')
        ];

        return $lang[$this->type];
    }

    /**
     * Mutator for field name attribute.
     *
     * @return string
     */
    public function getTranslatedFieldAttribute()
    {
        if (empty($this->field)) {
            return '<>';
        }

        return Lang::get(self::REQUIRED_FIELDS[$this->field]);
    }

    /**
     * Get type options.
     *
     * @return array
     */
    public function getTypeOptions()
    {
        if ($this->field) {
            return [
                self::TYPE_TEXT    => 'elipce.limesurvey::lang.backend.columns.text_option',
            ];
        }

        return [
            self::TYPE_TEXT    => 'elipce.limesurvey::lang.backend.columns.text_option',
            self::TYPE_NUMERIC => 'elipce.limesurvey::lang.backend.columns.numeric_option',
            self::TYPE_DATE    => 'elipce.limesurvey::lang.backend.columns.date_option'
        ];
    }
}