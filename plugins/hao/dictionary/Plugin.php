<?php namespace Hao\Dictionary;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;

/**
 * Dictionary Plugin Information File
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
            'name'        => 'hao.dictionary::lang.plugin.name',
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
            'Hao\Dictionary\Components\MyComponent' => 'myComponent',
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

            'hao.dictionary.guest_vocabulary' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.guest_vocabulary',
            ],

            'hao.dictionary.create_vocabulary' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.create_vocabulary',
            ],

            'hao.dictionary.manage_vocabulary' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.manage_vocabulary',
            ],

            'hao.dictionary.delete_vocabulary' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.delete_vocabulary',
            ],

            'hao.dictionary.create_translation' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.create_translation',
            ],

            'hao.dictionary.manage_translation' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.manage_translation',
            ],

            'hao.dictionary.delete_translation' => [
                'tab'   => 'hao.dictionary::lang.permission.label.all_vocabulary',
                'label' => 'hao.dictionary::lang.permission.label.delete_translation',
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
            'dictionary' => [
                'label'       => 'hao.dictionary::lang.plugin.name',
                'url'         => Backend::url('hao/dictionary/index/index'),
                'icon'        => 'icon-book',
                'permissions' => ['hao.dictionary.*'],
                'order'       => 500,

                'sideMenu' => [
                    'vocabulary' => [
                        'label' => Lang::get('hao.dictionary::lang.plugin.menus.vocabulary'),
                        'icon' => 'icon-hand-lizard-o',
                        'url' => Backend::url('hao/dictionary/vocabularies'),
                        'permissions' => ['hao.dictionary.*'],
                    ],

                    'translation' => [
                        'label' => Lang::get('hao.dictionary::lang.plugin.menus.translation'),
                        'icon' => 'icon-file-text-o',
                        'url' => Backend::url('hao/dictionary/translations'),
                        'permissions' => ['hao.dictionary.*'],
                    ],
                ]
            ],
        ];
    }
}
