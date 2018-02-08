<?php namespace Elipce\Comments;

use Backend\Facades\Backend;
use Elipce\BiPage\Controllers\Pages;
use Elipce\BiPage\Models\Page;
use Illuminate\Support\Facades\Event;
use System\Classes\PluginBase;
use Backend\Models\User;

/**
 * Class Plugin
 * @package Elipce\Comments
 */
class Plugin extends PluginBase
{

    /**
     * Dependencies
     * @var array
     */
    public $require = ['Elipce.Multisite', 'RainLab.User', 'Elipce.BiPage'];

    /**
     * Plugin details
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.comments::lang.plugin.name',
            'description' => 'elipce.comments::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-comments'
        ];
    }

    /**
     * Register components
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Elipce\Comments\Components\Comments' => 'comments',
            'Elipce\Comments\Components\LastComments' => 'lastComments',
        ];
    }

    /**
     * Register permissions
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.bipage.access_comments' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.comments::lang.plugin.permissions.access_comments'
            ]
        ];
    }

    /**
     * Register navigation
     * @return array
     */
    public function registerNavigation()
    {
        return [];
    }

    /**
     * Boot plugin method
     */
    public function boot()
    {
        /*
         * Extend Backend User model
         */
        User::extend(function ($model) {
            // Add relation
            $model->hasMany['comments'] = ['Elipce\Comments\Models\Comment', 'key' => 'author_id'];
        });

        /*
         * Extend Page model
         */
        Page::extend(function ($model) {
            // Add relation
            $model->hasMany['comments'] = ['Elipce\Comments\Models\Comment', 'key' => 'page_id'];
        });

        /*
         * Extend backend menus
         */
        Event::listen('backend.menu.extendItems', function ($manager) {
            // Add comments menu to bipage
            $manager->addSideMenuItems('Elipce.BiPage', 'bipage', [
                'comments' => [
                    'label' => 'elipce.comments::lang.plugin.name',
                    'url' => Backend::url('elipce/comments/comments'),
                    'icon' => 'icon-comments',
                    'permissions' => ['elipce.bipage.access_comments'],
                ]
            ]);
        });

        /*
         * Extend Pages controller form
         */
        Pages::extendFormFields(function ($form, $model, $context) {
            if ($model instanceof Page) {
                // Add commented switch button
                $form->addFields([
                    'commented' => [
                        'label' => 'elipce.comments::lang.backend.commented_label',
                        'commentAbove' => 'elipce.comments::lang.backend.commented_comment',
                        'type' => 'switch',
                        'default' => true,
                        'context' => 'update'
                    ]
                ], 'secondary');
            }
        });
    }
}