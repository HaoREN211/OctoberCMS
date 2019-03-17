<?php namespace Hao\Cv;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;

/**
 * Cv Plugin Information File
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
            'name'        => 'Cv',
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
            'Hao\Cv\Components\MyComponent' => 'myComponent',
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
            'hao.cv.some_permission' => [
                'tab' => 'Cv',
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
            'cv' => [
                'label'       => Lang::get('hao.cv::lang.plugin.cv.name'),
                'url'         => Backend::url('hao/cv/index/index'),
                'icon'        => 'icon-address-card',
                'permissions' => ['hao.cv.*'],
                'order'       => 20,
            ],
        ];
    }
}
