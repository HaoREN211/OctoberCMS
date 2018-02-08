<?php namespace Elipce\News;

use System\Classes\PluginBase;
use Elipce\Multisite\Models\Portal;
use Illuminate\Support\Facades\Event;
use Backend\Facades\Backend;

/**
 * News Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Dependencies
     * @var array
     */
    public $require = ['Elipce.BiPage', 'Elipce.Multisite'];

    /**
     * Returns information about this plugin.
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.news::lang.plugin.name',
            'description' => 'elipce.news::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-newspaper-o'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     * @return array
     */
    public function registerComponents()
    {
        return ['Elipce\News\Components\News' => 'news'];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.multisite.access_news' => [
                'tab' => 'elipce.multisite::lang.plugin.name',
                'label' => 'elipce.news::lang.plugin.permissions.access_news'
            ]
        ];
    }

    /**
     * Boot plugin method
     */
    public function boot()
    {
        /*
         * Extend Portal model
         */
        Portal::extend(function ($model) {
            $model->hasMany['news'] = ['Elipce\News\Models\News', 'key' => 'portal_id'];
        });

        /*
         * Extend Multisite sidebar menu
         */
        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('Elipce.Multisite', 'multisite', [
                'news' => [
                    'label' => 'elipce.news::lang.plugin.name',
                    'icon' => 'icon-newspaper-o',
                    'url' => Backend::url('elipce/news/news'),
                    'permissions' => ['elipce.multisite.access_news'],
                    'order' => 4
                ]
            ]);
        });
    }
}
