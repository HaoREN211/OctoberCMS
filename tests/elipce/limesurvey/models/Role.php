<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Model;

/**
 * Role Model
 */
class Role extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|unique:elipce_limesurvey_roles,name'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.limesurvey::lang.backend.roles.name_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_roles';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'participants' => ['Elipce\LimeSurvey\Models\Participant', 'key' => 'role_id'],
        'surveys'      => ['Elipce\LimeSurvey\Models\Survey', 'key' => 'role_id']
    ];

    /**
     * Mutator for slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return str_slug($this->name, '_');
    }
}