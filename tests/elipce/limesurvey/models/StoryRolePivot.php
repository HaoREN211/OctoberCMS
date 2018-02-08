<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Pivot;

/**
 * Pivot Model StoryRolePivot
 */
class StoryRolePivot extends Pivot
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'code' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'code' => 'elipce.limesurvey::lang.backend.roles.code_label'
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'code'
    ];
}