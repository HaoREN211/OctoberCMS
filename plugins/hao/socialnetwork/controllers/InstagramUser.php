<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Hao\Socialnetwork\Classes\Twitter\Twitter as HaoTwitter;
use Flash;
use Illuminate\Support\Facades\Lang;
use Hao\Socialnetwork\Classes\Instagram\User as HaoInstagramUser;
use Backend;
use Hao\Socialnetwork\Classes\Instagram\Follower as HaoFollower;


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

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

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
        $resultat = $instagram->saveUser();
        if($resultat != null){
            Flash::success(Lang::get('hao.socialnetwork::lang.instagram.updatedSuccess'));
            return Backend::redirect('hao/socialnetwork/instagramuser/update/'.$resultat);
        }
        else
        {
            Flash::success(Lang::get('hao.socialnetwork::lang.instagram.updatedError'));
        }
    }


    /**
     * @param $id
     * @return mixed
     */
    public function onQuite($id){
        return Backend::redirect('hao/socialnetwork/instagramuser');
    }


    public function onGetFollower($id=null){

        $data = post();
        if(is_array($data) &&
            array_key_exists('username', $data))
        {
            $follower = new HaoFollower($data['username']);
            $follower->synchronizationFollower();
        }
    }
}
