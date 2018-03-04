<?php namespace Hao\Socialnetwork;

use Backend;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\Lang;
use Backend\Facades\BackendMenu;

use Backend\Models\User as BackendUserModel;
use Backend\Controllers\Users as BackendUserController;
use Illuminate\Support\Facades\Event;
use Backend\Facades\BackendAuth;


/**
 * Socialnetwork Plugin Information File
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
            'name'        => 'Socialnetwork',
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
        BackendMenu::registerContextSidenavPartial(
            'Hao.Socialnetwork',
            'socialnetwork',
            '$/hao/socialnetwork/partials/_sidebar.htm');
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->extendModels();
        $this->extendForms();
    }


    /**
     * Extend backend user models
     */
    protected function extendModels()
    {
        BackendUserModel::extend(function ($model) {
            $model->belongsTo['twitter'] = ['Hao\Socialnetwork\Models\TwitterUser',
                'table' => 'hao_socialnetwork_twitter_users',
                'key' => 'twitter_id',
                'otherKey' => 'id'];
        });
    }


    /**
     * Extend backend user forms
     */
    protected function extendForms()
    {
        BackendUserController::extendFormFields(function($form, $model, $context){

            if (!$model instanceof BackendUserModel)
                return;

            $form->addTabFields([
                'twitter' => [
                    'label'=> 'hao.socialnetwork::lang.twitter.name',
                    'type'=>'relation',
                    'nameFrom'=> 'name',
                    'span' => 'auto',
                    'tab' => 'hao.socialnetwork::lang.plugin.name'
                ],
            ]);

        });
    }




    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Hao\Socialnetwork\Components\MyComponent' => 'myComponent',
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

            'hao.socialnetwork.access_twitter_token' => [
                'tab' => 'hao.socialnetwork::lang.twitter.group',
                'label' => 'hao.socialnetwork::lang.permissions.twitter.access_twitter_token',
            ],

            'hao.socialnetwork.access_meetics' => [
                'tab' => 'hao.socialnetwork::lang.plugin.menus.meetics',
                'label' => 'hao.socialnetwork::lang.permissions.meetic.access_meetics',
            ],

            'hao.socialnetwork.access_twitter' =>[
                'tab' => 'hao.socialnetwork::lang.twitter.group',
                'label' => 'hao.socialnetwork::lang.permissions.twitter.access_twitter',
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
        $user = BackendAuth::getUser();
        $twitterId = $user->twitter->id;

        return [
            'socialnetwork' => [
                'label'       => Lang::get('hao.socialnetwork::lang.plugin.name'),
                'url'         => Backend::url('hao/socialnetwork/index/index'),
                'icon'        => 'icon-commenting-o',
                'permissions' => ['hao.socialnetwork.*'],
                'order'       => 100,

                'sideMenu' => [

                    'twitter_token' =>[
                        'label' => Lang::get('hao.socialnetwork::lang.twitter.token.description'),
                        'icon' => 'icon-database',
                        'url' => Backend::url('hao/socialnetwork/twittertokens'),
                        'permissions'   => ['hao.socialnetwork.access_twitter_token'],
                        'group'         => 'hao.socialnetwork::lang.twitter.group',
                    ],

                    'my_twitter_user' =>[
                        'label' => Lang::get('hao.socialnetwork::lang.twitter.myAccount'),
                        'icon' => 'icon-twitter',
                        'url' => Backend::url('hao/socialnetwork/twitterusers/update/'.$twitterId),
                        'permissions'   => ['hao.socialnetwork.access_twitter'],
                        'group'         => 'hao.socialnetwork::lang.twitter.group',
                    ],

                    'twitter_new_user' =>[
                        'label' => Lang::get('hao.socialnetwork::lang.backend.twitter.user.new'),
                        'icon' => 'icon-user-plus',
                        'url' => Backend::url('hao/socialnetwork/twitterusers/new'),
                        'permissions'   => ['hao.socialnetwork.access_twitter'],
                        'group'         => 'hao.socialnetwork::lang.twitter.group',
                    ],

                    'twitter_user' =>[
                        'label' => Lang::get('hao.socialnetwork::lang.twitter.description'),
                        'icon' => 'icon-user',
                        'url' => Backend::url('hao/socialnetwork/twitterusers'),
                        'permissions'   => ['hao.socialnetwork.access_twitter'],
                        'group'         => 'hao.socialnetwork::lang.twitter.group',
                    ],

                    'meetics' => [
                        'label' => Lang::get('hao.socialnetwork::lang.plugin.menus.meetics'),
                        'icon' => 'icon-maxcdn',
                        'url' => Backend::url('hao/socialnetwork/meetics'),
                        'permissions' => ['hao.socialnetwork.access_meetics'],
                        'group' => 'hao.socialnetwork::lang.plugin.menus.group',
                    ],


                ]
            ],
        ];
    }
}
