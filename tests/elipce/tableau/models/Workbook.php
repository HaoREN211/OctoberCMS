<?php namespace Elipce\Tableau\Models;

use Elipce\BiPage\Contracts\Visualization;
use Elipce\BiPage\Models\Page;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * Model Workbook
 */
class Workbook extends Model implements Visualization
{

    /**
     * Traits
     */
    use \Elipce\BiPage\Traits\Visualization;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_tableau_workbooks';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'contentUrl',
        'showTabs',
        'project_name',
        'external_id'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'views'       => ['Elipce\Tableau\Models\View', 'key' => 'workbook_id'],
        'views_count' => ['Elipce\Tableau\Models\View', 'key' => 'workbook_id', 'count' => true]
    ];
    public $belongsTo = [
        'site' => ['Elipce\Tableau\Models\Site', 'key' => 'site_id']
    ];
    public $belongsToMany = [
        'groups' => [
            'Elipce\Tableau\Models\Group',
            'table'    => 'elipce_tableau_subscriptions',
            'key'      => 'workbook_id',
            'otherKey' => 'group_id'
        ]
    ];

    /**
     * Scope a query to only include workbooks of a given group.
     *
     * @param Builder $query
     * @param int $groupId
     * @return Builder
     */
    public function scopeApplyGroup($query, $groupId)
    {
        return $query->join('elipce_tableau_subscriptions', 'elipce_tableau_subscriptions.workbook_id', '=',
            'elipce_tableau_workbooks.id')
            ->where('elipce_tableau_subscriptions.group_id', $groupId)
            ->select('elipce_tableau_workbooks.*');
    }

    /**
     * Mutator for first view url attribute.
     *
     * @return string
     */
    public function getFirstViewUrlAttribute()
    {
        $viewUrl = $this->views->first()->contentUrl;
        $explodedUrl = explode('/', $viewUrl);

        return $explodedUrl[0] . '/' . $explodedUrl[2];
    }

    /**
     * Check if a given user can access to the workbook.
     *
     * @param User $user
     * @return bool
     */
    public function canAccess(User $user = null)
    {
        if (! $user) {
            return false;
        }
        if (! $user->tableau_viewer) {
            return false;
        }

        return $this->groups->intersect($user->tableau_viewer->groups)->count() > 0;
    }

    /**
     * Generate the dashboard partial parameters.
     *
     * @param Page $page
     * @param User $user
     * @return array
     */
    public function buildPartialParameters(Page $page, User $user = null)
    {
        return [
            '~/plugins/elipce/tableau/partials/_workbook.htm',
            [
                'siteRoot' => $this->site->url,
                'name'     => $this->firstViewUrl
            ]
        ];
    }

    /**
     * Get dashboard title.
     *
     * @return string
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
        return 'Tableau Online';
    }
}