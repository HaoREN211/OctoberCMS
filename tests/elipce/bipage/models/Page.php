<?php namespace Elipce\BiPage\Models;

use Elipce\Multisite\Facades\Multisite;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * Model Page
 */
class Page extends Model
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
        'thumbnail'  => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'       => 'elipce.bipage::lang.backend.pages.name_label',
        'collection' => 'elipce.bipage::lang.backend.pages.collection_label',
        'thumbnail'  => 'elipce.bipage::lang.backend.pages.thumbnail_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bipage_pages';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'published',
        'excerpt'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'bookmarks' => ['Elipce\BiPage\Models\Bookmark', 'key' => 'page_id']
    ];
    public $belongsTo = [
        'collection' => ['Elipce\BiPage\Models\Collection', 'key' => 'collection_id']
    ];
    public $belongsToMany = [
        'folders'            => [
            'Elipce\BiPage\Models\Folder',
            'table'    => 'elipce_bipage_folders_pages',
            'key'      => 'page_id',
            'otherKey' => 'folder_id'
        ],
        'dashboards'         => [
            'Elipce\BiPage\Models\Dashboard',
            'table'    => 'elipce_bipage_pages_dashboards',
            'key'      => 'page_id',
            'otherKey' => 'dashboard_id',
            'pivot'    => ['subtitle', 'source', 'description', 'help', 'position']
        ],
        'ordered_dashboards' => [
            'Elipce\BiPage\Models\Dashboard',
            'table'    => 'elipce_bipage_pages_dashboards',
            'key'      => 'page_id',
            'otherKey' => 'dashboard_id',
            'order'    => 'elipce_bipage_pages_dashboards.position asc',
            'pivot'    => ['subtitle', 'source', 'description', 'help', 'position']
        ]
    ];
    public $attachOne = ['thumbnail' => 'System\Models\File'];

    /**
     * Scope a query to only include pages of a given portal.
     *
     * @param Builder $query
     * @param int $portalId
     * @return Builder
     */
    public function scopeApplyPortal($query, $portalId)
    {
        return $query->join('elipce_bipage_folders_pages', 'elipce_bipage_folders_pages.page_id', '=',
            'elipce_bipage_pages.id')
            ->join('elipce_bipage_folders', 'elipce_bipage_folders.id', '=', 'elipce_bipage_folders_pages.folder_id')
            ->where('elipce_bipage_folders.portal_id', $portalId)
            ->select('elipce_bipage_pages.*');
    }

    /**
     * Scope a query to only include bookmarked pages
     * of a given user on a given portal.
     *
     * @param Builder $query
     * @param int $portalId
     * @param int $userId
     *
     * @return Builder
     */
    public function scopeBookmarked($query, $portalId, $userId)
    {
        return $query->join('elipce_bipage_bookmarks', 'elipce_bipage_bookmarks.page_id', '=', 'elipce_bipage_pages.id')
            ->where('elipce_bipage_bookmarks.portal_id', $portalId)
            ->where('elipce_bipage_bookmarks.user_id', $userId)
            ->select('elipce_bipage_pages.*');
    }

    /**
     * Scope a query to only include allowed pages
     * for a given backend user.
     *
     * @param Builder $query
     * @param User $user
     * @param bool $published
     *
     * @return Builder
     */
    public function scopeIsAllowed($query, $user, $published = true)
    {
        if ($published) {
            $query->published();
        }

        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->whereIn('collection_id', $user->collections);
    }

    /**
     * Scope for published pages.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    /**
     * Scope for unpublished pages.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeUnpublished($query)
    {
        return $query->where('published', false);
    }

    /**
     * Mutator for public attribute.
     * (ie: ALL his dasboards and his content are available)
     *
     * @return bool
     */
    public function getPublicAttribute()
    {
        return $this->canAccess(null);
    }

    /**
     * Check a given user can access to the page.
     * (ie: user has access to all page's dashboards and page is published)
     *
     * @param User $user
     * @return bool
     */
    public function canAccess(User $user = null)
    {
        /*
         * Must be published
         */
        if (! $this->published) {
            return false;
        }

        try {
            /*
             * User must have access to ALL page's dashboards
             */
            $this->dashboards->each(function($dashboard) use ($user) {
                if (! $dashboard->canAccess($user)) {
                    throw new \Exception;
                }
            });
        } catch (\Exception $e) {
            return false;
        }

        /*
         * Shared page => check if user is on his portal
         */
        if ($this->shared && ! empty($user)) {
            return Multisite::getPortal()->id == $user->portal->id;
        } else {
            /*
             * Everything is alright
             */
            return true;
        }
    }
}