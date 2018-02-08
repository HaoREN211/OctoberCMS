<?php namespace Elipce\BiPage\Models;

use Elipce\BiPage\Contracts\Visualization;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * Model Dashboard
 */
class Dashboard extends Model
{

    /**
     * Traits
     */
    use \System\Traits\ViewMaker;

    /**
     * The resolved dashboard model
     *
     * @var null|mixed
     */
    protected $resolvedModel = null;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bipage_dashboards';

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
        'model_class'
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'collections' => [
            'Elipce\BiPage\Models\Collection',
            'table'    => 'elipce_bipage_collections_dashboards',
            'key'      => 'dashboard_id',
            'otherKey' => 'collection_id'
        ]
    ];

    /**
     * Scope a query to only include dashboards of a given collection.
     *
     * @param Builder $query
     * @param int $collectionId
     * @return Builder
     */
    public function scopeApplyCollection($query, $collectionId)
    {
        return $query->join('elipce_bipage_collections_dashboards', 'elipce_bipage_collections_dashboards.dashboard_id',
            '=', 'elipce_bipage_dashboards.id')
            ->where('elipce_bipage_collections_dashboards.collection_id', $collectionId)
            ->select('elipce_bipage_dashboards.*');
    }

    /**
     * Resolve implemented dashboard model.
     * @return Visualization
     */
    protected function resolveDashboard()
    {
        if ($this->resolvedModel === null) {
            $class = $this->model_class;
            $this->resolvedModel = $class::findOrFail($this->id);
        }

        return $this->resolvedModel;
    }

    /**
     * Mutator for visibility attribute.
     *
     * @return bool
     */
    public function getPublicAttribute()
    {
        return $this->canAccess(null);
    }

    /**
     * Check if a given user can access this dashboard.
     *
     * @param User $user
     * @return boolean
     */
    public function canAccess(User $user = null)
    {
        return $this->resolveDashboard()->canAccess($user);
    }

    /**
     * Render the dashboard partial.
     *
     * @param Page $page
     * @param User $user
     * @return string
     */
    public function renderPartial(Page $page, User $user = null)
    {
        $params = $this->resolveDashboard()->buildPartialParameters($page, $user);

        return call_user_func_array([$this, 'makePartial'], $params);
    }
}