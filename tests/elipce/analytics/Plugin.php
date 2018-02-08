<?php namespace Elipce\Analytics;

use Backend\Facades\Backend;
use Illuminate\Support\Facades\Event;
use System\Classes\PluginBase;

/**
 * Analytics Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Dependencies plugins
     * @var array
     */
    public $require = ['Elipce.BiPage'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.analytics::lang.plugin.name',
            'description' => 'elipce.analytics::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-line-chart'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     * @return array
     */
    public function registerComponents()
    {
    }

    /**
     * Registers any back-end permissions used by this plugin.
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.datasources.analytics.access_accounts' => [
                'tab' => 'elipce.analytics::lang.plugin.name',
                'label' => 'elipce.analytics::lang.plugin.permissions.access_accounts',
            ],
            'elipce.datasources.analytics.access_views' => [
                'tab' => 'elipce.analytics::lang.plugin.name',
                'label' => 'elipce.analytics::lang.plugin.permissions.access_views',
            ],
            'elipce.datasources.analytics.access_charts' => [
                'tab' => 'elipce.analytics::lang.plugin.name',
                'label' => 'elipce.analytics::lang.plugin.permissions.access_charts',
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
     * Plugin boot method
     */
    public function boot()
    {
        /*
         * Extend backend menus
         */
        Event::listen('backend.menu.extendItems', function($manager) {
            // Add Analytics plugin menus to datasources collapsible menu
            $manager->addSideMenuItems('Elipce.BiPage', 'datasources', [
                'analytics_accounts' => [
                    'label' => 'elipce.analytics::lang.plugin.menus.accounts',
                    'icon' => 'icon-wrench',
                    'url' => Backend::url('elipce/analytics/accounts'),
                    'permissions' => ['elipce.datasources.analytics.access_accounts'],
                    'group' => 'elipce.analytics::lang.plugin.name'
                ],
                'analytics_views' => [
                    'label' => 'elipce.analytics::lang.plugin.menus.views',
                    'icon' => 'icon-eye',
                    'url' => Backend::url('elipce/analytics/views'),
                    'permissions' => ['elipce.datasources.analytics.access_views'],
                    'group' => 'elipce.analytics::lang.plugin.name'
                ],
                'analytics_charts' => [
                    'label' => 'elipce.analytics::lang.plugin.menus.charts',
                    'icon' => 'icon-magic',
                    'url' => Backend::url('elipce/analytics/charts'),
                    'permissions' => ['elipce.datasources.analytics.access_charts'],
                    'group' => 'elipce.analytics::lang.plugin.name'
                ]
            ]);
        });
    }
}
