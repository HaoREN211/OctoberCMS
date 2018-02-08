<?php namespace Elipce\Bime;

use Backend\Controllers\Users as BackendUsers;
use Backend\Facades\BackendAuth as Auth;
use Backend\Facades\Backend;
use Elipce\Bime\Classes\BimeService;
use Elipce\Bime\Models\Dashboard;
use Elipce\Bime\Models\Group;
use Elipce\BiPage\Controllers\Collections;
use Elipce\BiPage\Models\Collection;
use Illuminate\Support\Facades\Event;
use RainLab\User\Controllers\Users;
use System\Classes\PluginBase;
use RainLab\User\Models\User;
use Backend\Models\User as BackendUser;
use Elipce\Bime\Models\Account;

/**
 * Bime Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Dependencies plugins
     * @var array
     */
    public $require = ['RainLab.User', 'Elipce.BiPage'];

    /**
     * Plugin details
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.bime::lang.plugin.name',
            'description' => 'elipce.bime::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-bar-chart',
            'homepage' => 'http://www.elipce.com'
        ];
    }

    /**
     * Plugin permissions
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.datasources.bime.access_accounts' => [
                'tab' => 'elipce.bime::lang.plugin.name',
                'label' => 'elipce.bime::lang.plugin.permissions.access_accounts'
            ],
            'elipce.datasources.bime.access_groups' => [
                'tab' => 'elipce.bime::lang.plugin.name',
                'label' => 'elipce.bime::lang.plugin.permissions.access_groups'
            ],
            'elipce.datasources.bime.access_dashboards' => [
                'tab' => 'elipce.bime::lang.plugin.name',
                'label' => 'elipce.bime::lang.plugin.permissions.access_dashboards'
            ],
            'elipce.datasources.bime.access_viewers' => [
                'tab' => 'elipce.bime::lang.plugin.name',
                'label' => 'elipce.bime::lang.plugin.permissions.access_viewers'
            ],
            'elipce.datasources.bime.access_parameters' => [
                'tab' => 'elipce.bime::lang.plugin.name',
                'label' => 'elipce.bime::lang.plugin.permissions.access_'
            ],
            'elipce.datasources.bime.access_filters' => [
                'tab' => 'elipce.bime::lang.plugin.name',
                'label' => 'elipce.bime::lang.plugin.permissions.access_filters'
            ]
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     * @return array
     */
    public function registerNavigation()
    {
        return [];
    }

    /**
     * Schedule tasks
     * @param $schedule
     */
    public function registerSchedule($schedule)
    {
        /*
         * Synchronize BIME accounts' data every day
         */
        $schedule->call(function () {
            foreach (Account::all() as $account) {
                $bimeService = new BimeService($account);
                $bimeService->synchronize();
            }
        })->daily();
    }

    /**
     * Plugin boot
     */
    public function boot()
    {
        /*
         * Extensions
         */
        $this->extendModels();
        $this->extendControllers();
        $this->extendMenus();
    }

    /**
     * Extend controllers
     */
    protected function extendControllers()
    {
        /*
         * Collections controller
         */
        Collections::extend(function ($controller) {
            // Add new behavior to handle synchronization
            $controller->implement[] = 'Elipce.Bime.Behaviors.SynchronizationController';
        });
        Collections::extendFormFields(function ($form, $model, $context) {
            $form->addFields([
                'bime_section' => [
                    'label' => 'elipce.bime::lang.backend.collections.bime_section_label',
                    'tab' => 'elipce.bime::lang.backend.collections.tab',
                    'type' => 'section',
                    'span' => 'left'
                ],
                'bime_sync' => [
                    'tab' => 'elipce.bime::lang.backend.collections.tab',
                    'type' => 'partial',
                    'path' => '$/elipce/bime/partials/_sync_button.htm',
                    'context' => 'update',
                    'span' => 'right'
                ],
                'bime_accounts' => [
                    'tab' => 'elipce.bime::lang.backend.collections.tab',
                    'label' => 'elipce.bime::lang.backend.collections.accounts_label',
                    'commentAbove' => 'elipce.bime::lang.backend.collections.accounts_comment',
                    'type' => 'checkboxlist',
                    'span' => 'full'
                ],
                'bime_groups' => [
                    'tab' => 'elipce.bime::lang.backend.collections.tab',
                    'label' => 'elipce.bime::lang.backend.collections.groups_label',
                    'commentAbove' => 'elipce.bime::lang.backend.collections.groups_comment',
                    'type' => 'checkboxlist',
                    'dependsOn' => 'bime_accounts',
                    'span' => 'full'
                ]
            ], 'primary');
        });

        /*
         * Backend Users controller
         */
        BackendUsers::extendFormFields(function ($form, $model, $context) {
            if (!$model->hasAccess('elipce.datasources.bime.access_viewers')) return;
            if (!Auth::getUser()->isSuperUser()) return;
            if ($model->isSuperUser()) return;

            $form->addFields([
                'bime_groups_section' => [
                    'label' => 'elipce.bime::lang.backend.backend_users.bime_groups_section_label',
                    'comment' => 'elipce.bime::lang.backend.backend_users.bime_groups_section_comment',
                    'tab' => 'elipce.bime::lang.backend.backend_users.relation_tab',
                    'type' => 'section'
                ],
                'bime_groups' => [
                    'tab' => 'elipce.bime::lang.backend.backend_users.relation_tab',
                    'type' => 'checkboxlist'
                ]
            ], 'primary');
        });

        /*
         * Frontend Users controller
         */
        Event::listen('backend.list.extendQuery', function ($widget, $query) {
            if ($widget->getController() instanceof Users) {
                if (Auth::getUser()->isSuperUser()) return;
                if (Auth::getUser()->bime_groups == null) return;
                // Filter list on backend user's Bime groups
                $query->orWhere(function ($subquery) {
                    $subquery->join('viewers', 'users.email', '=', 'viewers.login')
                        ->whereIn('viewers.group_id', Auth::getUser()->bime_groups);
                });
                // Filter list on backend user's portal (for frontend users who have no group)
                $query->orWhere(function ($subquery) {
                    $subquery->leftJoin('elipce_bime_viewers', 'users.email', '=', 'viewers.login')
                        ->whereNull('viewers.id')->where('portal_id', '=', Auth::getUser()->portal->id);
                });
            }
        });
        Users::extendFormFields(function ($form, $model, $context) {
            if (!Auth::getUser()->hasAccess('elipce.datasources.bime.access_viewers')) return;
            if ($model->bime_viewer == null) return;

            $form->addFields([
                'bime_viewer' => [
                    'label' => 'elipce.bime::lang.backend.users.viewer_label',
                    'commentAbove' => 'elipce.bime::lang.backend.users.viewer_comments',
                    'tab' => 'elipce.bime::lang.backend.users.relation_tab',
                    'path' => '$/elipce/bime/partials/_viewer.htm',
                    'context' => 'preview',
                    'type' => 'partial',
                    'span' => 'left'
                ],
                'bime_group' => [
                    'label' => 'elipce.bime::lang.backend.users.group_label',
                    'commentAbove' => 'elipce.bime::lang.backend.users.group_comments',
                    'tab' => 'elipce.bime::lang.backend.users.relation_tab',
                    'path' => '$/elipce/bime/partials/_group.htm',
                    'context' => 'preview',
                    'type' => 'partial',
                    'dependsOn' => 'bime_viewer',
                    'span' => 'right'
                ]
            ], 'primary');
        });
    }

    /**
     * Extend models
     */
    protected function extendModels()
    {
        /*
         * Frontend User model
         */
        User::extend(function ($model) {
            // Add relation
            $model->hasOne['bime_viewer'] = ['Elipce\Bime\Models\Viewer', 'key' => 'login', 'otherKey' => 'email'];
        });

        /*
         * Backend User model
         */
        BackendUser::extend(function ($model) {
            // Add jsonable field
            $model->jsonable(array_merge($model->getJsonable(), ['bime_groups']));
            $model->addFillable('bime_groups');
            // Add method to populate dropdown
            $model->addDynamicMethod('getBimeGroupsOptions', function () {
                return Group::all()->lists('fullname', 'id');
            });
            // Clean Bime groups
            $model->bindEvent('model.beforeSave', function () use ($model) {
                // Remove Bime groups if not group manager
                if (!$model->hasAccess('elipce.datasources.bime.access_viewers') && count($model->bime_groups) > 0) {
                    $model->bime_groups = null;
                }
            });
        });

        /*
         * Collection model
         */
        Collection::extend(function ($model) {
            // Add jsonable fields
            $model->jsonable(['bime_accounts', 'bime_groups']);
            // Add methods to populate dropdowns
            $model->addDynamicMethod('getBimeAccountsOptions', function () use ($model) {
                return Account::all()->lists('name', 'id');
            });
            $model->addDynamicMethod('getBimeGroupsOptions', function () use ($model) {
                return Group::whereIn('account_id', $model->bime_accounts)
                    ->lists('name', 'id');
            });
            // Bind events
            $model->bindEvent('model.beforeSave', function () use ($model) {
                // No account <=> no groups
                if (empty($model->bime_accounts)) {
                    $model->bime_groups = null;
                } else if (empty($model->bime_groups)) {
                    $model->bime_accounts = null;
                }
            });
            $model->bindEvent('model.afterSave', function () use ($model) {
                // Fetch Bime allowed dashboards
                $allowed = Dashboard::join('elipce_bime_subscriptions', 'elipce_bime_subscriptions.dashboard_id', '=', 'elipce_bime_dashboards.id')
                    ->whereIn('account_id', $model->bime_accounts)
                    ->whereIn('group_id', $model->bime_groups)
                    ->lists('id');
                // Fetch other allowed dashboards
                $allowed = array_merge(
                    $model->dashboards()
                        ->where('type', '<>', 'Bime')
                        ->lists('elipce_bipage_dashboards.id'), $allowed);
                // Synchronize associations
                $model->dashboards()->sync($allowed);
            });
        });
    }

    /**
     * Extend backend menus
     */
    protected function extendMenus()
    {
        /*
         * Add Bime plugin menus to datasources collapsible menu
         */
        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('Elipce.BiPage', 'datasources', [
                'bime_accounts' => [
                    'label' => 'elipce.bime::lang.plugin.menus.accounts',
                    'icon' => 'icon-wrench',
                    'url' => Backend::url('elipce/bime/accounts'),
                    'permissions' => ['elipce.datasources.bime.access_accounts'],
                    'group' => 'elipce.bime::lang.plugin.name'
                ],
                'bime_dashboards' => [
                    'label' => 'elipce.bime::lang.plugin.menus.dashboards',
                    'icon' => 'icon-area-chart',
                    'url' => Backend::url('elipce/bime/dashboards'),
                    'permissions' => ['elipce.datasources.bime.access_dashboards'],
                    'group' => 'elipce.bime::lang.plugin.name'
                ],
                'bime_filters' => [
                    'label' => 'elipce.bime::lang.plugin.menus.filters',
                    'icon' => 'icon-sliders',
                    'url' => Backend::url('elipce/bime/filters'),
                    'permissions' => ['elipce.datasources.bime.access_filters'],
                    'group' => 'elipce.bime::lang.plugin.name'
                ],
                'bime_groups' => [
                    'label' => 'elipce.bime::lang.plugin.menus.groups',
                    'icon' => 'icon-users',
                    'url' => Backend::url('elipce/bime/groups'),
                    'permissions' => ['elipce.datasources.bime.access_groups'],
                    'group' => 'elipce.bime::lang.plugin.name'
                ],
                'bime_viewers' => [
                    'label' => 'elipce.bime::lang.plugin.menus.viewers',
                    'icon' => 'icon-book',
                    'url' => Backend::url('elipce/bime/viewers'),
                    'permissions' => ['elipce.datasources.bime.access_viewers'],
                    'group' => 'elipce.bime::lang.plugin.name'
                ]
            ]);
        });
    }
}