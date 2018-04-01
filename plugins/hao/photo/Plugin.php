<?php namespace Hao\Photo;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;

/**
 * Photo Plugin Information File
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
            'name'        => 'Photo',
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
            'Hao\Photo\Components\MyComponent' => 'myComponent',
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
            'hao.photo.some_permission' => [
                'tab' => Lang::get('hao.photo::lang.permission.photo.tab'),
                'label' =>  Lang::get('hao.photo::lang.permission.photo.label'),
            ],

            'hao.photo.photo_create'    =>  [
                'tab' => Lang::get('hao.photo::lang.permission.photo_create.tab'),
                'label' =>  Lang::get('hao.photo::lang.permission.photo_create.label'),
            ],

            'hao.photo.photo_manage'    =>  [
                'tab' => Lang::get('hao.photo::lang.permission.photo_manage.tab'),
                'label' =>  Lang::get('hao.photo::lang.permission.photo_manage.label'),
            ]
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
            'photo' => [
                'label'       => 'Photo',
                'url'         => Backend::url('hao/photo/index/index'),
                'icon'        => 'icon-picture-o',
                'permissions' => ['hao.photo.*'],
                'order'       => 101,


                'sideMenu' => [
                    'photo_creating' =>   [
                        'label' => Lang::get('hao.photo::lang.menu.photo.create'),
                        'icon' => 'icon-database',
                        'url' => Backend::url('hao/photo/photo/create'),
                        'permissions'   => ['hao.photo.photo_create'],
                    ],

                    'photo_managing' =>   [
                        'label' => Lang::get('hao.photo::lang.menu.photo.manage'),
                        'icon' => 'icon-picture-o',
                        'url' => Backend::url('hao/photo/photo'),
                        'permissions'   => ['hao.photo.photo_manage'],
                    ],
                ]
            ],
        ];
    }
}
