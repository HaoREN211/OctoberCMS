<?php namespace Hao\Job;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;
use BackendMenu;

/**
 * Job Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Job',
            'description' => 'No description provided yet...',
            'author'      => 'Hao',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        BackendMenu::registerContextSidenavPartial(
            'Hao.Job',
            'job',
            '$/hao/job/partials/_sidebar.htm');
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Hao\Job\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'hao.job.basic' => [
                'tab' => Lang::get('hao.job::lang.permission.job'),
                'label' => Lang::get('hao.job::lang.permission.jobLabel'),
            ],

            'hao.job.liepin_create' => [
                'tab' => Lang::get('hao.job::lang.permission.liepin.create'),
                'label' => Lang::get('hao.job::lang.permission.liepin.createLabel'),
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'job' => [
                'label'       => Lang::get('hao.job::lang.navigation.index'),
                'url'         => Backend::url('hao/job/index/index'),
                'icon'        => 'icon-leaf',
                'permissions' => ['hao.job.*'],
                'order'       => 100,

                'sideMenu' => [
                    'liepinCreate' =>   [
                        'label' => Lang::get('hao.job::lang.liepin.menu.create'),
                        'icon' => 'icon-database',
                        'url' => Backend::url('hao/job/liepin/create'),
                        'permissions'   => ['hao.job.liepin_create'],
                        'group'         => 'hao.job::lang.group.create',
                    ],
                ]
            ]
        ];
    }
}
