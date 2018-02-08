<?php namespace Elipce\LimeSurvey\Models;

use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Template Model
 */
class Template extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'       => 'required',
        'collection' => 'required:create',
        'structure'  => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'       => 'elipce.limesurvey::lang.backend.templates.name_label',
        'collection' => 'elipce.limesurvey::lang.backend.templates.collection_label',
        'structure'  => 'elipce.limesurvey::lang.backend.templates.structure_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_templates';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'presurveys'       => ['Elipce\LimeSurvey\Models\PreSurvey', 'key' => 'template_id'],
        'linked_questions' => ['Elipce\LimeSurvey\Models\Question', 'key' => 'template_id', 'scope' => 'linked'],
        'questions'        => ['Elipce\LimeSurvey\Models\Question', 'key' => 'template_id']
    ];
    public $belongsTo = [
        'collection' => ['Elipce\BiPage\Models\Collection', 'key' => 'collection_id']
    ];
    public $attachOne = [
        'structure' => 'System\Models\File'
    ];

    /**
     * Scope a query to only include allowed templates
     * for a given backend user.
     *
     * @param Builder $query
     * @param BackendUser $user
     * @return Builder
     */
    public function scopeIsAllowed($query, $user)
    {
        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->whereIn('collection_id', $user->collections);
    }
}