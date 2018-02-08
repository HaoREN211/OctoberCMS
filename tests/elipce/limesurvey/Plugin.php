<?php namespace Elipce\LimeSurvey;

use Backend\Facades\Backend;
use Elipce\LimeSurvey\Classes\LimeSurveyService;
use Elipce\LimeSurvey\Models\Session;
use Elipce\Multisite\Controllers\Portals;
use Elipce\Multisite\Models\Portal;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use System\Classes\PluginBase;

/**
 * LimeSurvey Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Dependencies
     *
     * @var array
     */
    public $require = ['Elipce.BiPage', 'Elipce.Multisite'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'elipce.limesurvey::lang.plugin.name',
            'description' => 'elipce.limesurvey::lang.plugin.description',
            'author'      => 'Elipce Informatique',
            'icon'        => 'icon-lemon-o'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.limesurvey.access_accounts'  => [
                'tab'   => 'elipce.limesurvey::lang.plugin.name',
                'label' => 'elipce.limesurvey::lang.plugin.permissions.access_accounts'
            ],
            'elipce.limesurvey.access_templates' => [
                'tab'   => 'elipce.limesurvey::lang.plugin.name',
                'label' => 'elipce.limesurvey::lang.plugin.permissions.access_templates'
            ],
            'elipce.limesurvey.access_stories'   => [
                'tab'   => 'elipce.limesurvey::lang.plugin.name',
                'label' => 'elipce.limesurvey::lang.plugin.permissions.access_stories'
            ],
            'elipce.limesurvey.access_sessions'  => [
                'tab'   => 'elipce.limesurvey::lang.plugin.name',
                'label' => 'elipce.limesurvey::lang.plugin.permissions.access_sessions'
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
            'limesurvey' => [
                'label'       => 'elipce.limesurvey::lang.plugin.name',
                'url'         => Backend::url('elipce/limesurvey/index/index'),
                'icon'        => 'icon-lemon-o',
                'permissions' => ['elipce.limesurvey.*'],
                'order'       => 25,

                'sideMenu' => [
                    'accounts'  => [
                        'label'       => 'elipce.limesurvey::lang.plugin.menus.accounts',
                        'icon'        => 'icon-wrench',
                        'url'         => Backend::url('elipce/limesurvey/accounts'),
                        'permissions' => ['elipce.limesurvey.access_accounts'],
                        'order'       => 1
                    ],
                    'templates' => [
                        'label'       => 'elipce.limesurvey::lang.plugin.menus.templates',
                        'icon'        => 'icon-flask',
                        'url'         => Backend::url('elipce/limesurvey/templates'),
                        'permissions' => ['elipce.limesurvey.access_templates'],
                        'order'       => 2
                    ],
                    'stories'   => [
                        'label'       => 'elipce.limesurvey::lang.plugin.menus.stories',
                        'icon'        => 'icon-film',
                        'url'         => Backend::url('elipce/limesurvey/stories'),
                        'permissions' => ['elipce.limesurvey.access_stories'],
                        'order'       => 3
                    ],
                    'sessions'  => [
                        'label'       => 'elipce.limesurvey::lang.plugin.menus.sessions',
                        'icon'        => 'icon-calendar',
                        'url'         => Backend::url('elipce/limesurvey/sessions'),
                        'permissions' => ['elipce.limesurvey.access_sessions'],
                        'order'       => 4
                    ]
                ]
            ]
        ];
    }

    /**
     * Schedule tasks.
     *
     * @param $schedule
     */
    public function registerSchedule($schedule)
    {
        /*
         * Launch invitations and duplicate linked answers every ten minutes
         */
        $schedule->call(function() {
            Session::all()->each(function($session) {
                /*
                 * Only active sessions
                 */
                if ($session->evaluationStartDate->isPast() && $session->evaluationEndDate->isFuture()) {
                    $limesurveyService = new LimeSurveyService($session);
                    $limesurveyService->inviteParticipants();
                    $limesurveyService->duplicateAllAnswers();
                }
            });
        })->everyTenMinutes();
        /*
         * Clean up archived surveys
         */
        $schedule->call(function() {
            Session::all()->each(function($session) {
                if ($session->evaluationEndDate->isPast()) {
                    $limesurveyService = new LimeSurveyService($session);
                    $limesurveyService->clean();
                }
            });
        })->daily();
    }

    /**
     * Plugin boot
     */
    public function boot()
    {
        /*
         * Register Excel service provider
         */
        App::register('Maatwebsite\Excel\ExcelServiceProvider');
        $alias = AliasLoader::getInstance();
        $alias->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');

        /*
         * Extending Portal model
         */
        Portal::extend(function($model) {
            // Add relation
            $model->belongsTo['limesurvey_account'] = [
                'Elipce\LimeSurvey\Models\Account',
                'key' => 'limesurvey_account_id'
            ];
        });

        /*
         * TODO: delete this when OBS/EPS integration is completed
         */
        Event::listen('backend.form.extendFields', function($widget) {
            /*
             * Only Portals controller
             */
            if (! $widget->getController() instanceof Portals) {
                return;
            }
            /*
             * Add an extra organization field
             */
            $widget->addFields([
                'organization' => [
                    'label'        => 'Organisation',
                    'commentAbove' => 'Organisation rattachée au portail.',
                    'type'         => 'text'
                ]
            ]);
        });
    }

}