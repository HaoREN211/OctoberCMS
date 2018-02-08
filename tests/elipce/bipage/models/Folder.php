<?php namespace Elipce\BiPage\Models;

use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * Model Folder
 */
class Folder extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'   => 'required',
        'portal' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'   => 'elipce.bipage::lang.backend.folders.name_label',
        'portal' => 'elipce.bipage::lang.backend.folders.portal_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bipage_folders';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'description',
        'position'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'portal' => ['Elipce\Multisite\Models\Portal', 'key' => 'portal_id']
    ];
    public $belongsToMany = [
        'pages'         => [
            'Elipce\BiPage\Models\Page',
            'table'    => 'elipce_bipage_folders_pages',
            'key'      => 'folder_id',
            'otherKey' => 'page_id',
            'pivot'    => ['position']
        ],
        'ordered_pages' => [
            'Elipce\BiPage\Models\Page',
            'table'    => 'elipce_bipage_folders_pages',
            'key'      => 'folder_id',
            'otherKey' => 'page_id',
            'order'    => 'elipce_bipage_folders_pages.position asc',
            'pivot'    => ['position']
        ],
        'pages_count'   => [
            'Elipce\BiPage\Models\Page',
            'table'    => 'elipce_bipage_folders_pages',
            'key'      => 'folder_id',
            'otherKey' => 'page_id',
            'count'    => true
        ]
    ];

    /**
     * Scope a query to only include allowed folders
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

        return $query->where('portal_id', $user->portal->id);
    }

    /**
     * Check a given user can access to the folder.
     *
     * @param User $user
     * @return bool
     */
    public function canAccess(User $user)
    {
        try {
            $this->pages->each(function($page) use ($user) {
                if ($page->canAccess($user)) {
                    throw new \Exception;
                }
            });
        } catch (\Exception $e) {
            return true;
        }

        return false;
    }
}