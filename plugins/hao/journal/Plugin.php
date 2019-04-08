<?php namespace Hao\Journal;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;

/**
 * Journal Plugin Information File
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
            'name'        => 'Journal',
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
            'Hao\Journal\Components\MyComponent' => 'myComponent',
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
            'hao.journal.some_permission' => [
                'tab' => 'Journal',
                'label' => 'Some permission'
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
            'journal' => [
                'label'       => Lang::get('hao.journal::lang.backend.journal.name'),
                'url'         => Backend::url('hao/journal/index/index'),
                'icon'        => 'icon-leaf',
                'permissions' => ['hao.journal.*'],
                'order'       => 25,

                'sideMenu' => [
                    'sub_journal' =>   [
                        'label' => Lang::get('hao.journal::lang.backend.journal.name'),
                        'icon' => 'icon-database',
                        'url' => Backend::url('hao/journal/journal/create'),
                    ],
                ]
            ],
        ];
    }
}
