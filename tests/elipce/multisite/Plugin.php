<?php namespace Elipce\Multisite;

use Backend\Controllers\Users as BackendUsers;
use Elipce\Multisite\Classes\MultisiteException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use RainLab\User\Controllers\Users;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Backend\Facades\BackendAuth as Auth;
use Backend\Models\User as BackendUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\App;
use Backend\Facades\Backend;
use Elipce\Multisite\Facades\Multisite;

/**
 * Multisite Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Dependencies
     */
    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'elipce.multisite::lang.plugin.name',
            'description' => 'elipce.multisite::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-cubes'
        ];
    }

    /**
     * Components
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Elipce\Multisite\Components\Portal' => 'portal',
            'Elipce\Multisite\Components\Redirector' => 'redirector'
        ];
    }

    /**
     * Permissions
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.multisite.access_portals' => [
                'label' => 'elipce.multisite::lang.plugin.permissions.access_portals',
                'tab' => 'elipce.multisite::lang.plugin.name'
            ]
        ];
    }

    /**
     * Navigation
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'multisite' => [
                'label' => 'elipce.multisite::lang.plugin.name',
                'url' => Backend::url('elipce/multisite/index/index'),
                'icon' => 'icon-cubes',
                'permissions' => ['elipce.multisite.*'],
                'order' => 20,

                'sideMenu' => [
                    'portals' => [
                        'label' => 'elipce.multisite::lang.plugin.name',
                        'icon' => 'icon-cubes',
                        'url' => Backend::url('elipce/multisite/portals'),
                        'permissions' => ['elipce.multisite.*'],
                        'order' => 1
                    ]
                ]
            ]
        ];
    }

    /**
     * Registers plugin commands, services and facades
     */
    public function register()
    {
        /* Register commands */
        $this->registerConsoleCommand('portal.list', 'Elipce\Multisite\Console\PortalList');
        $this->registerConsoleCommand('portal.create', 'Elipce\Multisite\Console\PortalCreate');
        $this->registerConsoleCommand('portal.remove', 'Elipce\Multisite\Console\PortalRemove');
        $this->registerConsoleCommand('portal.clear', 'Elipce\Multisite\Console\PortalClear');
        $this->registerConsoleCommand('portal.help', 'Elipce\Multisite\Console\PortalHelp');

        /* Register Multisite facade */
        $alias = AliasLoader::getInstance();
        $alias->alias('Multisite', 'Elipce\Multisite\Facades\Multisite');

        /* Instanciate singleton for facade */
        App::singleton('multisite.portal', function () {
            return \Elipce\Multisite\Classes\Multisite::instance();
        });
    }

    /**
     * Boot
     */
    public function boot()
    {
        /*
         * Extensions
         */
        $this->extendModels();
        $this->extendControllers();

        /*
         * Listen for CMS activeTheme event, change theme according to binds
         * If there's no match, let CMS set active theme
         */
        Event::listen('cms.theme.getActiveTheme', function () {
            return Multisite::getPortal()->theme;
        });

        /*
         * Set application URL according to current portal
         */
        Event::listen('backend.route', function () {
            try {
                Config::set('app.url', Multisite::getPortal()->url);
            } catch (MultisiteException $e) {
                Log::warning('No portal found when trying to build application URL.');
            }
        });

        /*
         * Extend RainLab.Editable component to handle portal security rules
         */
        Event::listen('rainlab.editable.check_editor', function ($component, $user) {
            $currentPortal = Multisite::getPortal();
            // No user
            if (empty($user))
                return false;
            // Super user
            if ($user->isSuperUser())
                return true;
            // Match with backend user's portal
            return $user->hasAccess('elipce.multisite.access_portals')
            && $user->portal->id == $currentPortal->id;
        });
    }

    /**
     * Extend models
     */
    protected function extendModels()
    {
        /*
         * Backend User model
         */
        BackendUser::extend(function ($model) {
            // Add relation
            $model->belongsTo['portal'] = ['Elipce\Multisite\Models\Portal', 'key' => 'portal_id'];
        });

        /*
         * Frontend User model
         */
        User::extend(function ($model) {
            // Add relations
            $model->belongsTo['portal'] = ['Elipce\Multisite\Models\Portal', 'key' => 'portal_id'];
            // Bind events
            $model->bindEvent('model.beforeCreate', function () use ($model) {
                $model->portal()->associate(Multisite::getPortal());
            });
        });
    }

    /**
     * Extend controllers
     */
    protected function extendControllers()
    {
        /*
         * Backend Users controller
         */
        BackendUsers::extendFormFields(function ($form, $model, $context) {
            if (!Auth::getUser()->isSuperUser()) return;
            if ($model->isSuperUser()) return;

            $form->addFields([
                'portal' => [
                    'label' => 'elipce.multisite::lang.backend.backend_users.relation_label',
                    'commentAbove' => 'elipce.multisite::lang.backend.backend_users.relation_comment',
                    'trigger' => [
                        'action' => 'hide',
                        'field' => 'is_superuser',
                        'condition' => 'checked'
                    ],
                    'type' => 'relation'
                ]
            ], 'secondary');
        });

        /*
         * Frontend User controller
         */
        Users::extendFormFields(function ($form, $model, $context) {
            if (!Auth::getUser()->isSuperUser()) return;

            $form->addFields([
                'portal' => [
                    'label' => 'elipce.multisite::lang.backend.users.relation_label',
                    'commentAbove' => 'elipce.multisite::lang.backend.users.relation_comment',
                    'span' => 'full',
                    'type' => 'relation'
                ]
            ], 'secondary');
        });
    }
}