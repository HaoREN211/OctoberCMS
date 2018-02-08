<?php namespace Elipce\Tableau\Models;

use Elipce\BiPage\Classes\CollectionHelper;
use Elipce\Tableau\Classes\TableauAuth;
use Elipce\Tableau\Classes\TableauRestClient;
use October\Rain\Database\Model;
use Carbon\Carbon;

/**
 * Class Site
 * @package Elipce\Tableau\Models
 */
class Site extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Encryptable;
    use \October\Rain\Database\Traits\Validation;

    /**
     * REST Client
     * @var null
     */
    protected $restClient = null;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'url' => 'required|unique:elipce_tableau_sites',
        'login' => 'required',
        'password' => 'required:create'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.tableau::lang.backend.sites.name_label',
        'url' => 'elipce.tableau::lang.backend.sites.url_label',
        'login' => 'elipce.tableau::lang.backend.sites.login_label',
        'password' => 'elipce.tableau::lang.backend.sites.password_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_tableau_sites';

    /**
     * @var array Encryptable fields
     */
    protected $encryptable = ['password', 'token'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'url',
        'login',
        'password',
        'token',
        'external_id',
        'owner_id'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'groups' => ['Elipce\Tableau\Models\Group', 'key' => 'site_id'],
        'viewers' => ['Elipce\Tableau\Models\Viewer', 'key' => 'site_id'],
        'workbooks' => ['Elipce\Tableau\Models\Workbook', 'key' => 'site_id']
    ];

    /**
     * Before the model is created.
     * (fill credentials)
     */
    public function beforeCreate()
    {
        $credentials = TableauAuth::login($this);
        $this->token = $credentials->token;
        $this->external_id = $credentials->siteId;
        $this->owner_id = $credentials->ownerId;
    }

    /**
     * Synchronize all site data with API.
     */
    public function synchronize()
    {
        $this->syncGroups();
        $this->syncWorkbooks();
        $this->syncViewers();
        $this->syncSubscriptions();
        $this->syncUsersGroups();
        $this->syncViews();
        $this->touch();
    }

    /**
     * Synchronize groups from API.
     */
    public function syncGroups()
    {
        // Fetch old and new groups
        $old = $this->groups()->get();
        $new = $this->getRestClient()
            ->get("sites/{$this->external_id}/groups")
            ->getResponse("group");

        // Synchronize two collections
        $groups = new CollectionHelper($old, $new, 'external_id');

        // Delete old groups
        Group::destroy($groups->deleted()->lists('id'));

        // Create new groups
        foreach ($groups->created() as $g) {
            $group = new Group($g);
            $group->site()->associate($this);
            $group->save();
        }

        // Update groups
        foreach ($groups->updated() as $g) {
            $group = Group::find($g['id']);
            $group->update($g);
        }
    }

    /**
     * Synchronize workbooks from API.
     */
    public function syncWorkbooks()
    {
        // Fetch old and new workbooks
        $old = $this->workbooks()->get();
        $new = $this->getRestClient()
            ->get("sites/{$this->external_id}/users/{$this->owner_id}/workbooks")
            ->getResponse('workbook', ['project' => 'name']);

        // Synchronize two collections
        $workbooks = new CollectionHelper($old, $new, 'external_id');

        // Delete old workbooks
        Workbook::destroy($workbooks->deleted()->lists('id'));

        // Create new workbooks
        foreach ($workbooks->created() as $w) {
            $workbook = new Workbook($w);
            $workbook->site()->associate($this);
            $workbook->save();
        }

        // Update workbooks
        foreach ($workbooks->updated() as $w) {
            $workbook = Workbook::find($w['id']);
            $workbook->update($w);
        }
    }

    /**
     * Synchronize views from API.
     */
    public function syncViews()
    {
        $this->workbooks->each(function ($workbook) {
            echo "\t {$workbook->name}\n";

            // Fetch old and new views
            $old = $workbook->views()->get();
            $new = $this->getRestClient()
                ->get("sites/{$this->external_id}/workbooks/{$workbook->external_id}/views")
                ->getResponse('view');

            // Synchronize two collections
            $views = new CollectionHelper($old, $new, 'external_id');

            // Delete old views
            View::destroy($views->deleted()->lists('id'));

            // Create new views
            foreach ($views->created() as $v) {
                // Create view's model
                $view = new View($v);
                // Create associations
                $view->site()->associate($this);
                $view->workbook()->associate($workbook);
                // Save viewer
                $view->save();
            }

            // Update viewers
            foreach ($views->updated() as $v) {
                $view = View::find($v['id']);
                $view->update($v);
            }
        });
    }

    /**
     * Synchronize viewers from API.
     */
    public function syncViewers()
    {
        // Fetch old and new viewers
        $old = $this->viewers()->get();
        $new = $this->getRestClient()
            ->get('sites/' . $this->external_id . '/users')
            ->getResponse('user');

        // Synchronize two collections
        $viewers = new CollectionHelper($old, $new, 'external_id');

        // Delete old viewers
        Viewer::destroy($viewers->deleted()->lists('id'));

        // Create new viewers
        foreach ($viewers->created() as $v) {
            // Create viewer's model
            $viewer = new Viewer([
                'name' => $v['name'],
                'external_id' => $v['external_id']
            ]);
            // Create associations
            $viewer->site()->associate($this);
            // Save viewer
            $viewer->save();
        }

        // Update viewers
        foreach ($viewers->updated() as $v) {
            $viewer = Viewer::find($v['id']);
            $viewer->update([
                'name' => $v['name'],
                'external_id' => $v['external_id']
            ]);
        }
    }

    /**
     * Synchronize groups / workbooks relation from API.
     */
    public function syncSubscriptions()
    {
        $this->workbooks->each(function ($workbook) {
            echo "\t {$workbook->name}\n";

            // API call
            $distantGroups = $this->getRestClient()
                ->get("sites/{$this->external_id}/workbooks/{$workbook->external_id}/permissions")
                ->getResponse('group')
                ->lists('external_id');

            $localGroups = $this->groups()
                ->whereIn('external_id', $distantGroups)
                ->lists('id');

            // Synchronize groups relation
            $workbook->groups()->sync($localGroups);
        });
    }

    /**
     * Synchronize users / groups relation from API.
     */
    public function syncUsersGroups()
    {
        $this->groups->each(function ($group) {
            echo "\t {$group->name}\n";

            // API call
            $distantViewers = $this->getRestClient()
                ->get("sites/{$this->external_id}/groups/{$group->external_id}/users")
                ->getResponse('user')
                ->lists('external_id');

            $localViewers = $this->viewers()
                ->whereIn('external_id', $distantViewers)
                ->lists('id');

            // Synchronize users relation
            $group->viewers()->sync($localViewers);
        });
    }

    /**
     * Get REST client.
     *
     * @return TableauRestClient
     */
    public function getRestClient()
    {
        // Update token if 240 minutes old
        if ($this->updated_at->diffInMinutes(Carbon::now()) > 240) {
            // Renew session
            $credentials = TableauAuth::login($this);
            $this->token = $credentials->token;
            $this->save();
        }

        if ($this->restClient == null) {
            // Save REST client
            $this->restClient = new TableauRestClient([
                'headers' => [
                    'X-Tableau-Auth: ' . $this->token
                ]
            ]);
        }

        return $this->restClient;
    }
}