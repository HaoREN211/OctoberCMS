<?php namespace Elipce\BiPage\Models;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use Backend\Models\User as BackendUser;

/**
 * Model Collection
 */
class Collection extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.bipage::lang.backend.collections.name_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bipage_collections';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'pages'       => ['Elipce\Bipage\Models\Page', 'key' => 'collection_id'],
        'pages_count' => ['Elipce\Bipage\Models\Page', 'key' => 'collection_id', 'count' => true]
    ];
    public $belongsToMany = [
        'dashboards'       => [
            'Elipce\BiPage\Models\Dashboard',
            'table'    => 'elipce_bipage_collections_dashboards',
            'key'      => 'collection_id',
            'otherKey' => 'dashboard_id'
        ],
        'dashboards_count' => [
            'Elipce\BiPage\Models\Dashboard',
            'table'    => 'elipce_bipage_collections_dashboards',
            'key'      => 'collection_id',
            'otherKey' => 'dashboard_id',
            'count'    => true
        ]
    ];

    /**
     * Scope a query to only include allowed comments
     * for a given backend user.
     *
     * @param Builder $query
     * @param BackendUser $user
     * @return Builder
     */
    public function scopeIsAllowed($query, BackendUser $user)
    {
        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->whereIn('id', $user->collections);
    }
}