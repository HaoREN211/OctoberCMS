<?php namespace Elipce\LimeSurvey\Models;

use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Story Model
 */
class Story extends Model
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
        'collection' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'       => 'elipce.limesurvey::lang.backend.stories.name_label',
        'collection' => 'elipce.limesurvey::lang.backend.stories.collection_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_stories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'presurveys'       => ['Elipce\LimeSurvey\Models\PreSurvey', 'key' => 'story_id'],
        'presurveys_count' => ['Elipce\LimeSurvey\Models\PreSurvey', 'key' => 'story_id', 'count' => true],
        'sessions'         => ['Elipce\LimeSurvey\Models\Session', 'key' => 'story_id'],
        'active_sessions'  => ['Elipce\LimeSurvey\Models\Session', 'key' => 'story_id', 'scope' => 'active'],
        'pending_sessions' => ['Elipce\LimeSurvey\Models\Session', 'key' => 'story_id', 'scope' => 'pending'],
        'expired_sessions' => ['Elipce\LimeSurvey\Models\Session', 'key' => 'story_id', 'scope' => 'expired'],
        'columns'          => ['Elipce\LimeSurvey\Models\Column', 'key' => 'story_id'],
        'mask_columns'     => ['Elipce\LimeSurvey\Models\Column', 'key' => 'story_id', 'scope' => 'mask'],
        'required_columns' => ['Elipce\LimeSurvey\Models\Column', 'key' => 'story_id', 'scope' => 'required']
    ];
    public $belongsTo = [
        'collection' => ['Elipce\BiPage\Models\Collection', 'key' => 'collection_id']
    ];
    public $belongsToMany = [
        'roles'       => [
            'Elipce\LimeSurvey\Models\Role',
            'table'      => 'elipce_limesurvey_stories_roles',
            'key'        => 'story_id',
            'otherKey'   => 'role_id',
            'pivotModel' => 'Elipce\LimeSurvey\Models\StoryRolePivot',
            'pivot'      => 'code'
        ],
        'roles_count' => [
            'Elipce\LimeSurvey\Models\Role',
            'table'    => 'elipce_limesurvey_stories_roles',
            'key'      => 'story_id',
            'otherKey' => 'role_id',
            'count'    => true
        ]
    ];
    public $attachOne = [
        'participants_file' => ['System\Models\File']
    ];

    /**
     * Scope a query to only include ready to use stories.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeReady($query)
    {
        return $query->join('elipce_limesurvey_presurveys', 'elipce_limesurvey_stories.id', '=',
            'elipce_limesurvey_presurveys.story_id')
            ->join('elipce_limesurvey_stories_roles', 'elipce_limesurvey_stories.id', '=',
                'elipce_limesurvey_stories_roles.story_id')
            ->select('elipce_limesurvey_stories.*');
    }

    /**
     * Scope a query to only include allowed stories
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