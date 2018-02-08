<?php namespace Elipce\BiPage\Models;

use Elipce\BiPage\Contracts\Visualization;
use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * Model Snippet
 */
class Snippet extends Model implements Visualization
{

    /**
     * Traits
     */
    use \Elipce\BiPage\Traits\Visualization;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'       => 'required',
        'code'       => 'required',
        'collection' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'       => 'elipce.bipage::lang.backend.snippets.name_label',
        'code'       => 'elipce.bipage::lang.backend.snippets.code_label',
        'collection' => 'elipce.bipage::lang.backend.images.collection_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bipage_snippets';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'code',
        'public'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'public' => 'boolean'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'collection' => [
            'Elipce\BiPage\Models\Collection',
            'table' => 'elipce_bipage_collections',
            'key'   => 'collection_id'
        ]
    ];

    /**
     * Scope a query to only include allowed snippets
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

    /**
     * Check if a given user can access to the snippet.
     *
     * @param User|null $user
     * @return bool
     */
    public function canAccess(User $user = null)
    {
        return $user !== null || $this->public;
    }

    /**
     * Build dashboard partial parameters.
     *
     * @param Page $page
     * @param User|null $user
     * @return array
     */
    public function buildPartialParameters(Page $page, User $user = null)
    {
        return [
            '~/plugins/elipce/bipage/partials/_snippet.htm',
            ['code' => $this->code]
        ];
    }

    /**
     * Get dashboard title.
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * Get dashboard type.
     *
     * @return string
     */
    public function getType()
    {
        return 'Snippet';
    }

    /**
     * Get dashboard visibility.
     *
     * @return bool
     */
    public function isPublic()
    {
        return boolval($this->public);
    }
}