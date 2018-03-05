<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Hao\Socialnetwork\Classes\Twitter\Twitter as HaoTwitter;
use Flash;
use Lang;
use Backend\Facades\Backend;
use Hao\Socialnetwork\Models\TwitterTweet as HaoTweet;

/**
 * Twitter Users Back-end Controller
 */
class TwitterUsers extends Controller
{
    private $twitter = "";

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig  = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Socialnetwork', 'socialnetwork', 'twitterusers');
        $this ->twitter = new HaoTwitter();
    }


    /**
     * get the friends
     * @param null $id
     * @return mixed
     */
    public function onGetFriend($id=null){
        $this->twitter->synchronizationFriend($id);
        Flash::success(Lang::get('hao.socialnetwork::lang.form.synchronization.twitter.friend'));
        return Backend::redirect("hao/socialnetwork/twitterusers/update/".$id);
    }

    /**
     * get the followers
     * @param null $id
     * @return mixed
     */
    public function onGetFollower($id = null){
        $this->twitter->synchronizationFollower($id);
        Flash::success(Lang::get('hao.socialnetwork::lang.form.synchronization.twitter.follower'));
        return Backend::redirect("hao/socialnetwork/twitterusers/update/".$id);
    }


    /**
     * return to twitter user list
     * @return mixed
     */
    public function onReturn(){
        return Backend::redirect("hao/socialnetwork/twitterusers");
    }


    /**
     * @return mixed
     */
    public function onReturnMainPage(){
        return Backend::redirect('hao/socialnetwork/index/index');
    }

    /**
     *
     */
    public function new(){
        ;
    }


    /**
     * @return mixed
     */
    public function onSaveNewTwitterUser(){
        $datas = post();
        $url = $datas["userUrl"];
        $screenName = HaoTwitter::getScreenNameFromUrl($url);
        $resultat = (string)$this->twitter->createNewUser($screenName);
        if($resultat ===(string)0)
            Flash::error(Lang::get('hao.socialnetwork::lang.flash.error.createnewuser'));
        else
        {
            Flash::success(Lang::get('hao.socialnetwork::lang.flash.success.createnewuser'));
            $redirectUrl = "hao/socialnetwork/twitterusers/update/".$resultat;
            return Backend::redirect($redirectUrl);
        }
    }



    public function onGetTweet($id=null){
        $this->twitter->synchronizationTweet($id);
        Flash::success(Lang::get('hao.socialnetwork::lang.form.synchronization.twitter.tweet'));
        $redirectUrl = "hao/socialnetwork/twitterusers/update/".$id;
        return Backend::redirect($redirectUrl);
    }

}
