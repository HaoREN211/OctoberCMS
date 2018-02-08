<?php namespace Elipce\Bime\Models;

use Elipce\BiPage\Contracts\Visualization;
use Backend\Models\User as BackendUser;
use Elipce\BiPage\Models\Page;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * Model Dashboard
 */
class Dashboard extends Model implements Visualization
{

    /**
     * Traits
     */
    use \Elipce\BiPage\Traits\Visualization;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bime_dashboards';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'publication_guid',
        'dashboard_folder',
        'is_public',
        'external_id'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'account' => ['Elipce\Bime\Models\Account', 'key' => 'account_id']
    ];
    public $belongsToMany = [
        'groups'     => [
            'Elipce\Bime\Models\Group',
            'table'    => 'elipce_bime_subscriptions',
            'key'      => 'dashboard_id',
            'otherKey' => 'group_id'
        ],
        'parameters' => [
            'Elipce\Bime\Models\Parameter',
            'table'    => 'elipce_bime_dashboards_parameters',
            'key'      => 'dashboard_id',
            'otherKey' => 'parameter_id',
            'pivot'    => ['parameter_name', 'position']
        ]
    ];

    /**
     * Scope a query to only include allowed viewers for a given backend user.
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

        return $query->join('elipce_bipage_collections_dashboards',
            'elipce_bipage_collections_dashboards.dashboard_id',
            '=', 'elipce_bime_dashboards.id')
            ->whereIn('collection_id', $user->collections)
            ->select('elipce_bime_dashboards.*');
    }

    /**
     * Scope a query to only include dashboards for a given group.
     *
     * @param Builder $query
     * @param int $groupId
     * @return Builder
     */
    public function scopeApplyGroup($query, $groupId)
    {
        return $query->join('elipce_bime_subscriptions', 'elipce_bime_subscriptions.dashboard_id', '=',
            'elipce_bime_dashboards.id')
            ->where('group_id', $groupId)
            ->select('elipce_bime_dashboards.*');
    }

    /**
     * Get folders options.
     *
     * @return array
     */
    public function getFoldersOptions()
    {
        return self::whereNotNull('dashboard_folder')->groupBy('dashboard_folder')
            ->lists('dashboard_folder', 'dashboard_folder');
    }

    /**
     * Generate a dashboard URL with access token and custom parameters.
     *
     * @param User $user
     * @param Page $page
     * @return string
     */
    protected function generateURL(Page $page, User $user = null)
    {
        $paramNames = $this->parameters->sortBy('pivot.position')
            ->lists('pivot.parameter_name', 'id');

        $parameters = [];

        /*
         * Get access token if available
         */
        if (! $this->public && $user->bime_viewer !== null) {
            $accessToken = $user->bime_viewer->access_token;
        }
        /*
         * Build query
         */
        $query = Filter::applyCollection($page->collection->id)
            ->applyPage($page->id)
            ->whereIn('parameter_id', array_keys($paramNames))
            ->orderBy('priority', 'asc');

        if (! $this->public && $user->bime_viewer !== null) {
            $query->applyGroup($user->bime_viewer->group->id)
                ->applyViewer($user->bime_viewer->id);
        }
        /*
         * Fetch all parameters value with priority
         */
        $query->get()->each(function($f) use (&$parameters, $paramNames) {
            $parameters[$paramNames[$f->parameter->id]] = $f->value;
        });
        /*
         * Order parameters according to dashboard's parameters order
         */
        if (count($parameters) > 0) {
            $parameters = array_merge(array_flip($paramNames), $parameters);
        }
        /*
         * Encode keys and values
         */
        $encoded = [];

        foreach ($parameters as $key => $value) {
            $encoded[rawurlencode($key)] = rawurlencode($value);
        }
        /*
         * Build base URL
         */
        $url = "{$this->account->url}/dashboard/{$this->publication_guid}";
        /*
         * Return base URL without parameter
         */
        if (empty($parameters) && empty($accessToken)) {
            return $url;
        }
        /*
         * Build parameters string query
         */
        $rawParameters = http_build_query($encoded);
        //$rawParameters = str_replace('%252C', ',', $rawParameters);
        //$rawParameters = str_replace('%2523', '#', $rawParameters);
        //$rawParameters = str_replace('%2520', '#', $rawParameters);
        /*
         * Return base URL with parameters
         */
        if (isset($accessToken)) {
            return "$url?access_token=$accessToken#$rawParameters";
        } else {
            return "$url#$rawParameters";
        }
    }

    /**
     * Build the dashboard partial parameters.
     *
     * @param User $user
     * @param Page $page
     * @return string
     */
    public function buildPartialParameters(Page $page, User $user = null)
    {
        return [
            '~/plugins/elipce/bime/partials/_dashboard.htm',
            ['url' => $this->generateURL($page, $user)]
        ];
    }

    /**
     * Mutator for dashboard visibility.
     *
     * @return bool
     */
    public function getPublicAttribute()
    {
        // TODO : use new Bime attributes !
        return count($this->groups->where('name', 'PUBLIC')) > 0;
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
        return 'Bime';
    }

    /**
     * Check if a given user can access to the dashboard.
     *
     * @param User $user
     * @return bool
     */
    public function canAccess(User $user = null)
    {
        /*
         * If no connected user, check if dashboard is public
         */
        if (! $user) {
            return $this->public;
        }
        /*
         * If user is connected but has not viewer, do the same
         */
        if (! $user->bime_viewer) {
            return $this->public;
        }
        /*
         * Check if user's viewer has access
         */
        return $this->groups->contains('id', $user->bime_viewer->group->id) || $this->public;
    }
}