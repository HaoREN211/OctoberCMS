<?php namespace Hao\Socialnetwork;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;

/**
 * Socialnetwork Plugin Information File
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
            'name'        => 'Socialnetwork',
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
            'Hao\Socialnetwork\Components\MyComponent' => 'myComponent',
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
            'hao.socialnetwork.access_meetics' => [
                'tab' => 'Socialnetwork',
                'label' => 'Some permission'
            ],

            'hao.socialnetwork.access_twitter' =>[
                'tab' => 'Socialnetwork',
                'label' => 'Twitter'
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
//        return [];

        return [
            'socialnetwork' => [
                'label'       => Lang::get('hao.socialnetwork::lang.plugin.name'),
                'url'         => Backend::url('hao/socialnetwork/index/index'),
                'icon'        => 'icon-commenting-o',
                'permissions' => ['hao.socialnetwork.*'],
                'order'       => 100,

                'sideMenu' => [

                    'twitter' =>[
                        'label' => Lang::get('hao.socialnetwork::lang.twitter.name'),
                        'icon' => 'icon-twitter',
                        'url' => Backend::url('hao/socialnetwork/twitter/index'),
                        'permissions' => ['hao.socialnetwork.access_twitter'],
                    ],

                    'meetics' => [
                        'label' => Lang::get('hao.socialnetwork::lang.plugin.menus.meetics'),
                        'icon' => 'icon-maxcdn',
                        'url' => Backend::url('hao/socialnetwork/meetics'),
                        'permissions' => ['hao.socialnetwork.access_meetics'],
                    ],


                ]
            ],
        ];
    }
}
