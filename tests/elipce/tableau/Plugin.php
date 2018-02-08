<?php namespace Elipce\Tableau;

use Backend\Facades\Backend;
use Illuminate\Support\Facades\Event;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

/**
 * Tableau Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Dependencies plugins
     * @var array
     */
    public $require = ['RainLab.User', 'Elipce.BiPage'];

    /**
     * Returns information about this plugin.
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.tableau::lang.plugin.name',
            'description' => 'elipce.tableau::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-line-chart',
            'homepage' => 'http://www.elipce.com'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Elipce\Tableau\Components\Logout' => 'tableau_logout'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.datasources.tableau.access_sites' => [
                'tab' => 'elipce.tableau::lang.plugin.name', 'label' => 'elipce.tableau::lang.plugin.permissions.access_sites'
            ],
            'elipce.datasources.tableau.access_groups' => [
                'tab' => 'elipce.tableau::lang.plugin.name', 'label' => 'elipce.tableau::lang.plugin.permissions.access_groups'
            ],
            'elipce.datasources.tableau.access_workbooks' => [
                'tab' => 'elipce.tableau::lang.plugin.name', 'label' => 'elipce.tableau::lang.plugin.permissions.access_workbooks'
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
     * Registers plugin commands
     */
    public function register()
    {
        $this->registerConsoleCommand('tableau.sync', 'Elipce\Tableau\Console\TableauSync');
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        /*
         * Extend Frontend User model
         */
        User::extend(function ($model) {
            // Add relation
            $model->hasOne['tableau_viewer'] = ['Elipce\Tableau\Models\Viewer', 'key' => 'name', 'otherKey' => 'email'];
        });

        /*
         * Extend backend menus
         */
        Event::listen('backend.menu.extendItems', function($manager) {
            // Add Tableau plugin menus to datasources collapsible menu
            $manager->addSideMenuItems('Elipce.BiPage', 'datasources', [
                'tableau_sites' => [
                    'label' => 'elipce.tableau::lang.plugin.menus.sites',
                    'icon' => 'icon-wrench',
                    'url' => Backend::url('elipce/tableau/sites'),
                    'permissions' => ['elipce.datasources.tableau.access_sites'],
                    'group' => 'elipce.tableau::lang.plugin.name'
                ],
                'tableau_workbooks' => [
                    'label' => 'elipce.tableau::lang.plugin.menus.workbooks',
                    'icon' => 'icon-line-chart',
                    'url' => Backend::url('elipce/tableau/workbooks'),
                    'permissions' => ['elipce.datasources.tableau.access_workbooks'],
                    'group' => 'elipce.tableau::lang.plugin.name'
                ],
                'tableau_groups' => [
                    'label' => 'elipce.tableau::lang.plugin.menus.groups',
                    'icon' => 'icon-users',
                    'url' => Backend::url('elipce/tableau/groups'),
                    'permissions' => ['elipce.datasources.tableau.access_groups'],
                    'group' => 'elipce.tableau::lang.plugin.name'
                ]
            ]);
        });
    }
}