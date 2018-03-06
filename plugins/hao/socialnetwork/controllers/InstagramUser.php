<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Hao\Socialnetwork\Classes\Twitter\Twitter as HaoTwitter;
use Flash;
use Illuminate\Support\Facades\Lang;
use Hao\Socialnetwork\Classes\Instagram\User as HaoInstagramUser;

/**
 * Instagram User Back-end Controller
 */
class InstagramUser extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Socialnetwork', 'socialnetwork', 'instagramuser');
    }


    public function new(){

    }

    /**
     * @return mixed
     */
    public function onReturnMainPage(){
        return Backend::redirect('hao/socialnetwork/index/index');
    }


    /**
     * @return mixed
     */
    public function onSaveNewInstagramUser(){
        $datas = post();
        $url = $datas["userUrl"];
        $screenName = HaoTwitter::getScreenNameFromUrl($url);

        $instagram = new HaoInstagramUser($screenName);
        $instagram->saveUser();
    }
}
