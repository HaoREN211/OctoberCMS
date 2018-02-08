<?php namespace Elipce\BiPage;

use Backend\Controllers\Users;
use Backend\Facades\BackendMenu;
use Elipce\Multisite\Controllers\Portals;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Elipce\Multisite\Models\Portal;
use Backend\Models\User as BackendUser;
use Illuminate\Support\Facades\Event;
use Backend\Facades\Backend;
use Elipce\BiPage\Models\Collection;
use Backend\Facades\BackendAuth as Auth;

/**
 * BiPage Plugin Information File
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
            'name' => 'elipce.bipage::lang.plugin.name',
            'description' => 'elipce.bipage::lang.plugin.description',
            'author' => 'Elipce Informatique',
            'icon' => 'icon-files-o',
            'homepage' => 'http://www.elipce.com'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Elipce\BiPage\Components\Page' => 'page',
            'Elipce\BiPage\Components\Folders' => 'folders',
            'Elipce\BiPage\Components\Focus' => 'focus',
            'Elipce\BiPage\Components\Showcase' => 'showcase',
            'Elipce\BiPage\Components\Bookmarks' => 'bookmarks',
            'Elipce\BiPage\Components\Search' => 'search'
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'elipce.bipage.access_pages' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_pages'
            ],
            'elipce.bipage.access_visualizations' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_visualizations'
            ],
            'elipce.bipage.access_folders' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_folders'
            ],
            'elipce.bipage.access_collections' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_collections'
            ],
            'elipce.bipage.preview_collections' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.preview_collections'
            ],
            'elipce.datasources.static.access_images' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_images'
            ],
            'elipce.datasources.static.access_snippets' => [
                'tab' => 'elipce.bipage::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_snippets'
            ],
            'elipce.multisite.access_focus' => [
                'tab' => 'elipce.multisite::lang.plugin.name',
                'label' => 'elipce.bipage::lang.plugin.permissions.access_focus'
            ]
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'bipage' => [
                'label' => 'elipce.bipage::lang.plugin.name',
                'url' => Backend::url('elipce/bipage/index/index'),
                'icon' => 'icon-files-o',
                'permissions' => ['elipce.bipage.*'],
                'order' => 10,

                'sideMenu' => [
                    'collections' => [
                        'label' => 'elipce.bipage::lang.plugin.menus.collections',
                        'icon' => 'icon-archive',
                        'url' => Backend::url('elipce/bipage/collections'),
                        'permissions' => ['elipce.bipage.access_collections', 'elipce.bipage.preview_collections']
                    ],
                    'folders' => [
                        'label' => 'elipce.bipage::lang.plugin.menus.folders',
                        'icon' => 'icon-folder-o',
                        'url' => Backend::url('elipce/bipage/folders'),
                        'permissions' => ['elipce.bipage.access_folders']
                    ],
                    'pages' => [
                        'label' => 'elipce.bipage::lang.plugin.menus.pages',
                        'icon' => 'icon-files-o',
                        'url' => Backend::url('elipce/bipage/pages'),
                        'permissions' => ['elipce.bipage.access_pages', 'elipce.bipage.access_visualizations']
                    ]
                ]
            ],
            'datasources' => [
                'label' => 'elipce.bipage::lang.plugin.menus.datasources',
                'url' => Backend::url('elipce/bipage/datasources/index'),
                'icon' => 'icon-leaf',
                'permissions' => ['elipce.datasources.*'],
                'order' => 15,

                'sideMenu' => [
                    'images' => [
                        'label' => 'elipce.bipage::lang.plugin.menus.images',
                        'icon' => 'icon-picture-o',
                        'url' => Backend::url('elipce/bipage/images'),
                        'permissions' => ['elipce.datasources.static.access_images'],
                        'group' => 'elipce.bipage::lang.plugin.menus.static'
                    ],
                    'snippets' => [
                        'label' => 'elipce.bipage::lang.plugin.menus.snippets',
                        'icon' => 'icon-rocket',
                        'url' => Backend::url('elipce/bipage/snippets'),
                        'permissions' => ['elipce.datasources.static.access_snippets'],
                        'group' => 'elipce.bipage::lang.plugin.menus.static'
                    ]
                ]
            ]
        ];
    }

    /**
     * Register plugin partials
     */
    public function register()
    {
        BackendMenu::registerContextSidenavPartial(
            'Elipce.BiPage',
            'datasources',
            '~/plugins/elipce/bipage/partials/_sidebar.htm'
        );
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
        $this->extendMenus();
    }

    /**
     * Extend models
     */
    protected function extendModels()
    {
        /*
         * Portal model
         */
        Portal::extend(function ($model) {
            // Add relations
            $model->hasMany['folders'] = ['Elipce\BiPage\Models\Folder', 'key' => 'portal_id', 'order' => 'position asc'];
            $model->hasMany['folders_count'] = ['Elipce\BiPage\Models\Folder', 'key' => 'portal_id', 'count' => true];
            $model->belongsToMany['focus'] = ['Elipce\BiPage\Models\Page', 'table' => 'elipce_bipage_focus', 'key' => 'portal_id', 'otherKey' => 'page_id'];
            $model->belongsToMany['focus_count'] = ['Elipce\BiPage\Models\Page', 'table' => 'elipce_bipage_focus', 'key' => 'portal_id', 'otherKey' => 'page_id', 'count' => true];
        });

        /*
         * Backend User model
         */
        BackendUser::extend(function ($model) {
            // Add jsonable field
            $model->jsonable(array_merge($model->getJsonable(), ['collections']));
            // Add fillable field
            $model->addFillable('collections');
            // Bind event
            $model->bindEvent('model.beforeSave', function () use ($model) {
                // Remove collections if not content manager
                if (!$model->hasAccess('elipce.bipage.*') && count($model->collections) > 0) {
                    $model->collections = null;
                }
            });
            // Populate collections checkbox list
            $model->addDynamicMethod('getCollectionsOptions', function () use ($model) {
                return Collection::all()->lists('name', 'id');
            });
            // Add relations
            $model->hasMany['images'] = ['Elipce\BiPage\Models\Image', 'key' => 'created_by'];
            $model->hasMany['snippets'] = ['Elipce\BiPage\Models\Snippet', 'key' => 'created_by'];
        });

        /*
         * Frontend User model
         */
        User::extend(function ($model) {
            // Add relation
            $model->hasMany['bookmarks'] = ['Elipce\BiPage\Models\Bookmark', 'key' => 'user_id'];
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
        Users::extendFormFields(function ($form, $model, $context) {
            if (!Auth::getUser()->isSuperUser()) return;
            if ($model->isSuperUser()) return;
            if (!$model->hasAccess('elipce.bipage.*')) return;

            $form->addFields([
                'collections_section' => [
                    'label' => 'elipce.bipage::lang.backend.backend_users.collections_section_label',
                    'comment' => 'elipce.bipage::lang.backend.backend_users.collections_section_comment',
                    'tab' => 'elipce.bipage::lang.backend.backend_users.relation_tab',
                    'type' => 'section'
                ],
                'collections' => [
                    'tab' => 'elipce.bipage::lang.backend.backend_users.relation_tab',
                    'type' => 'checkboxlist'
                ]
            ], 'primary');
        });

        /*
         * Portals controller
         */
        Portals::extend(function ($controller) {
            $controller->implement[] = 'Backend.Behaviors.RelationController';
            $controller->implement[] = 'Elipce.BiPage.Behaviors.ReorderRelationController';
            $controller->relationConfig = '$/elipce/bipage/controllers/folders/config_relation_portals.yaml';
            $controller->reorderRelationConfig = '$/elipce/bipage/controllers/folders/config_reorder_relation_portals.yaml';

        });
        Portals::extendFormFields(function ($form, $model, $context) {
            if (!Auth::getUser()->hasAccess('elipce.bipage.access_folders'))
                return;

            $form->addFields([
                'folders_section' => [
                    'label' => 'elipce.bipage::lang.backend.portals.folders_section_label',
                    'comment' => 'elipce.bipage::lang.backend.portals.folders_section_comment',
                    'tab' => 'elipce.bipage::lang.backend.portals.folders_tab',
                    'type' => 'section',
                    'context' => 'update',
                    'span' => 'full'
                ],
                'folders' => [
                    'tab' => 'elipce.bipage::lang.backend.portals.folders_tab',
                    'path' => '$/elipce/bipage/controllers/folders/_folders_field.htm',
                    'type' => 'partial',
                    'context' => 'update'
                ]
            ], 'primary');
        });
    }

    /**
     * Extend backend menus
     */
    protected function extendMenus()
    {
        /*
         * Add focus to Multisite sidebar menu
         */
        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('Elipce.Multisite', 'multisite', [
                'focus' => [
                    'label' => 'elipce.bipage::lang.plugin.menus.focus',
                    'icon' => 'icon-crosshairs',
                    'url' => Backend::url('elipce/bipage/focus'),
                    'permissions' => ['elipce.multisite.access_focus'],
                    'order' => 2
                ]
            ]);
        });
    }
}