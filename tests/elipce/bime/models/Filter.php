<?php namespace Elipce\Bime\Models;

use Backend\Models\User;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Filter Model
 */
class Filter extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Purgeable;

    /**
     * Validation rules
     */
    public $rules = [
        'parameter'  => 'required:create',
        'collection' => 'required:create',
        'value'      => 'required:update'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'parameter'  => 'elipce.bime::lang.backend.filters.parameter_label',
        'collection' => 'elipce.bime::lang.backend.filters.collection_label',
        'value'      => 'elipce.bime::lang.backend.filters.value_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bime_filters';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'value',
        'priority'
    ];

    /**
     * @var array Purgeable fields
     */
    protected $purgeable = ['viewer_switch'];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'parameter'  => ['Elipce\Bime\Models\Parameter', 'key' => 'parameter_id'],
        'collection' => ['Elipce\BiPage\Models\Collection', 'key' => 'collection_id'],
        'page'       => ['Elipce\BiPage\Models\Page', 'key' => 'page_id'],
        'group'      => ['Elipce\Bime\Models\Group', 'key' => 'group_id'],
        'viewer'     => ['Elipce\Bime\Models\Viewer', 'key' => 'viewer_id']
    ];

    /**
     * Before the model is saved.
     */
    public function beforeSave()
    {
        /*
         * Calculate priority
         */
        $this->priority = 0;

        if (! empty($this->page_id)) {
            $this->priority += 100;
        }

        if (! empty($this->viewer_id)) {
            $this->priority += 20;
        }

        if (! empty($this->group_id)) {
            $this->priority += 10;
        }
    }

    /**
     * Scope a query to only include allowed filters
     * for a given backend user.
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeIsAllowed($query, $user)
    {
        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->whereIn('collection_id', $user->collections);
    }

    /**
     * Scope a query to only include filters for a given collection.
     *
     * @param Builder $query
     * @param int $collectionId
     * @return Builder
     */
    public function scopeApplyCollection($query, $collectionId)
    {
        return $query->where('collection_id', $collectionId);
    }

    /**
     * Scope a query to only include filters for a given page.
     *
     * @param Builder $query
     * @param int $pageId
     * @return Builder
     */
    public function scopeApplyPage($query, $pageId)
    {
        return $query->where(function($query) use ($pageId) {
            $query->where('page_id', $pageId);
            $query->orWhereNull('page_id');
        });
    }

    /**
     * Scope a query to only include filters for a given group.
     *
     * @param Builder $query
     * @param int $groupId
     * @return Builder
     */
    public function scopeApplyGroup($query, $groupId)
    {
        return $query->where(function($query) use ($groupId) {
            $query->where('group_id', $groupId);
            $query->orWhereNull('group_id');
        });
    }

    /**
     * Scope a query to only include filters for a given viewer.
     *
     * @param Builder $query
     * @param int $viewerId
     * @return Builder
     */
    public function scopeApplyViewer($query, $viewerId)
    {
        return $query->where(function($query) use ($viewerId) {
            $query->where('viewer_id', $viewerId);
            $query->orWhereNull('viewer_id');
        });
    }

    /**
     * Mutator for viewer switch attribute.
     *
     * @return bool
     */
    public function getViewerSwitchAttribute()
    {
        return ! empty($this->viewer_id);
    }
}