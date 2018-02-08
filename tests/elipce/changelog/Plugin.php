<?php namespace Elipce\Changelog;

use Backend;
use Illuminate\Support\Facades\Event;
use System\Classes\PluginBase;

/**
 * Versioning Plugin Information File
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
            'name'        => 'Changelog',
            'description' => 'Permet de versionner une application.',
            'author'      => 'Elipce Informatique',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register report widgets
     * @return array
     */
    public function registerReportWidgets()
    {
        return [
            'Elipce\Changelog\ReportWidgets\Version' => [
                'label'   => 'Version',
                'context' => 'dashboard'
            ]
        ];
    }

    /**
     * Boot
     */
    public function boot()
    {
        // Remove Cms and Media from top menu
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->removeMainMenuItem('October.Cms', 'cms');
            $manager->removeMainMenuItem('October.Cms', 'media');
        });
    }
}