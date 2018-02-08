<?php namespace Elipce\Bime\Classes;

use Elipce\Bime\Models\Account;
use Elipce\Bime\Models\Dashboard;
use Elipce\Bime\Models\Group;
use Elipce\Bime\Models\Viewer;
use Elipce\BiPage\Classes\CollectionHelper;

/**
 * Class BimeService
 * @package Elipce\Bime\Classes
 */
class BimeService
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var null|BimeRestClient
     */
    protected $apiClient = null;

    /**
     * BimeService constructor.
     * @param $account Account
     */
    public function __construct($account)
    {
        $this->account = $account;
    }

    /**
     * API Client getter
     * @return BimeRestClient|null
     */
    protected function getApiClient()
    {
        if (empty($this->apiClient)) {
            $this->apiClient = new BimeRestClient([
                'headers' => [
                    'Authorization: Bearer ' . $this->account->token
                ]
            ]);
        }

        return $this->apiClient;
    }

    /**
     * Synchonize all account data from Bime API
     */
    public function synchronize()
    {
        $this->syncGroups();
        $this->syncDashboards();
        $this->syncSubscriptions();
        $this->syncViewers();
        $this->account->touch();
    }

    /**
     * Synchronize Bime Groups from API
     */
    protected function syncGroups()
    {
        // Fetch old and new groups
        $old = $this->account->groups()->get();
        $new = $this->getApiClient()->get('named_user_groups')->getResponse();

        // Synchronize two collections
        $groups = new CollectionHelper($old, $new, 'external_id');

        // Delete old groups
        $groupsToDelete = $groups->deleted()->lists('id')->all();
        Group::destroy($groupsToDelete);

        // Create new groups
        foreach ($groups->created() as $g) {
            $group = new Group($g);
            $group->account()->associate($this->account);
            $group->save();
        }

        // Update groups
        foreach ($groups->updated() as $g) {
            $group = Group::find($g['id']);
            $group->update($g);
        }
    }

    /**
     * Synchronize Bime Dashboards from API
     */
    protected function syncDashboards()
    {
        // Fetch old and new dashboards
        $old = $this->account->dashboards()->get();
        $new = $this->getApiClient()
            ->get('dashboards')
            ->getResponse()
            ->where('type', 'GroupedDashboard')
            ->where('is_published', true);

        // Synchronize two collections
        $dashboards = new CollectionHelper($old, $new, 'external_id');

        // Delete old dashboards
        $dashboardsToDelete = $dashboards->deleted()->lists('id')->all();
        Dashboard::destroy($dashboardsToDelete);

        // Create new dashboards
        foreach ($dashboards->created() as $d) {
            $dashboard = new Dashboard($d);
            $dashboard->account()->associate($this->account);
            $dashboard->save();
        }

        // Update dashboards
        foreach ($dashboards->updated() as $d) {
            $dashboard = Dashboard::find($d['id']);
            $dashboard->update($d);
        }

//        // Synchronize Bime Dashboards visibility from API
//        $this->account->dashboards->each(function ($dashboard) {
//            // Retrieve associated dashboard on Bime
//            $d = (object) $this->getApiClient()
//                ->get('dashboards/' . $dashboard->external_id)
//                ->getResponse();
//
//            // Update visibility attributes
//            $dashboard->is_public = ! ($d->password_protected || $d->group_security);
//            $dashboard->save();
//        });
    }

    /**
     * Synchronize Bime Subscriptions from API
     */
    protected function syncSubscriptions()
    {
        // Fetch subscriptions
        $data = $this->getApiClient()->get('dashboard_subscriptions')->getResponse();
        $data = $data->groupBy('named_user_group_id')->all();

        // Update each groups subscriptions
        foreach ($data as $key => $subscriptions) {
            // Dashboards' extern ids
            $ids = [];

            // Get ids
            foreach ($subscriptions as $subscription) {
                $ids[] = $subscription['dashboard_id'];
            }

            // Fetch real ids
            $dashboards = $this->account->dashboards()->whereIn('external_id', $ids)->lists('id');

            // Get group in database and  update constraints
            $group = $this->account->groups()->where('external_id', $key)->first();
            if ($group !== null) $group->dashboards()->sync($dashboards);
        }
    }

    /**
     * Synchronize Bime Viewers from API
     */
    protected function syncViewers()
    {
        // Fetch old and new viewers
        $old = $this->account->viewers()->get();
        $new = $this->getApiClient()->get('named_users')->getResponse();

        // Synchronize two collections
        $viewers = new CollectionHelper($old, $new, 'external_id');

        // Delete old viewers
        $viewersToDelete = $viewers->deleted()->lists('id')->all();
        Viewer::destroy($viewersToDelete);

        // Create new viewers
        foreach ($viewers->created() as $v) {
            // Fetch viewer's group
            $group = $this->account->groups()
                ->where('external_id', $v['named_user_group_id'])
                ->first();

            // Create viewer's model
            $viewer = new Viewer([
                'external_id' => $v['external_id'],
                'access_token' => $v['access_token'],
                'login' => $v['login']
            ]);

            // Create associations
            $viewer->account()->associate($this->account);
            $viewer->group()->associate($group);

            // Save viewer
            $viewer->save();
        }

        // Update viewers
        foreach ($viewers->updated() as $v) {
            $viewer = Viewer::find($v['id']);
            $viewer->update($v);

            // Update viewer's group
            $group = $this->account->groups()
                ->where('external_id', $v['named_user_group_id'])
                ->first();

            $viewer->group()->associate($group);
        }
    }

    /**
     * Update viewer's group on Bime
     * @param Viewer $viewer
     */
    public function updateViewer(Viewer $viewer)
    {
        $this->apiClient->put('named_users/' . $viewer->external_id, [
            'named_user_group_id' => $viewer->group->external_id
        ]);
    }

}