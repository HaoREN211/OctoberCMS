<?php namespace Elipce\SSO;

use Backend\Facades\Backend;
use Elipce\SSO\Facades\SingleSignOnHelper;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * SSO Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Dependencies
     * @var array
     */
    public $require = ['RainLab.User', 'Elipce.Multisite'];

    /**
     * Returns information about this plugin.
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'elipce.sso::lang.plugin.name',
            'description' => 'elipce.sso::lang.plugin.description',
            'author'      => 'Elipce Informatique',
            'icon'        => 'icon-key'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Elipce\SSO\Components\SingleSignOnButton' => 'ssobutton'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'backend.manage_sso'  => [
                'tab' => 'system::lang.permissions.name',
                'label' => 'elipce.sso::lang.plugin.permissions.manage_saml'
            ]
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     * @return array
     */
    public function registerNavigation()
    {
        return [];
    }

    /**
     * Registers back-end settings for this plugin.
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'elipce.sso::lang.settings.menu_label',
                'description' => 'elipce.sso::lang.settings.menu_description',
                'icon' => 'icon-key',
                'class' => 'Elipce\SSO\Models\Settings',
                'permissions' => ['backend.manage_saml'],
                'category' => 'system::lang.system.categories.system'
            ],
            'logs' => [
                'label' => 'elipce.sso::lang.settings.logs_label',
                'description' => 'elipce.sso::lang.settings.logs_description',
                'icon' => 'icon-key',
                'url' => Backend::url('elipce/sso/logs'),
                'permissions' => ['system.access_logs'],
                'category' => SettingsManager::CATEGORY_LOGS,
            ]
        ];
    }

    /**
     * Register commands, services and facades
     */
    public function register()
    {
        /*
         * Register SingleSignOnHelper facade
         */
        $alias = AliasLoader::getInstance();
        $alias->alias('SingleSignOnHelper', 'Elipce\SSO\Facades\SingleSignOnHelper');

        /*
         * Instanciate singleton for facade
         */
        App::singleton('sso.helper', function () {
            return \Elipce\SSO\Classes\SingleSignOnHelper::instance();
        });
    }

}
