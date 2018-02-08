<?php namespace Elipce\Tracker;

use Backend\Facades\BackendAuth;
use Elipce\Multisite\Controllers\Portals;
use System\Classes\PluginBase;

/**
 * Tracker Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Dependencies
     * @var array
     */
    public $require = ['Elipce.Multisite'];

    /**
     * Returns information about this plugin.
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.tracker::lang.plugin.name',
            'description' => 'elipce.tracker::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-eye'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Elipce\Tracker\Components\GoogleAnalytics' => 'ga'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     * @return array
     */
    public function registerPermissions()
    {
        return [];
    }

    /**
     * Registers back-end navigation items for this plugin.
     * @return array
     */
    public function registerNavigation()
    {
        return [];
    }

    public function boot()
    {
        /*
         * Extend Portals controller form
         */
        Portals::extendFormFields(function ($form, $model, $context) {
            if (!BackendAuth::getUser()->isSuperUser()) return;

            $form->addTabFields([
                'gaid' => [
                    'label' => 'elipce.tracker::lang.backend.portals.gaid_label',
                    'commentAbove' => 'elipce.tracker::lang.backend.portals.gaid_comment',
                    'tab' => 'elipce.multisite::lang.backend.portals.backend_tab',
                    'type' => 'text',
                    'span' => 'right'
                ]
            ]);
        });
    }
}
