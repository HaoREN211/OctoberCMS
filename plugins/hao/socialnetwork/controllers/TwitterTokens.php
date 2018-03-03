<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Facades\Backend;
use Backend\Classes\Controller;
use Hao\Socialnetwork\Models\TwitterToken;
use Hao\Socialnetwork\Classes\Base64encoder as Hao_base_64_encoder;
use Hao\Socialnetwork\Classes\Twitter\Token as twitterApiToken;
//twitterApiToken

/**
 * Twitter Tokens Back-end Controller
 */
class TwitterTokens extends Controller
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

        BackendMenu::setContext('Hao.Socialnetwork', 'socialnetwork', 'twittertokens');

    }

    /**
     * @param null $id
     */
    public function onSynchronization($id = null){
        /**
         * Save the data of frontend
         */
        $datas = post();

        $twitter_token = TwitterToken::find($id);
        $twitter_token->consumer_key = $datas['TwitterToken']['consumer_key'];
        $twitter_token->consumer_secret = $datas['TwitterToken']['consumer_secret'];
        $twitter_token->access_token = $datas['TwitterToken']['access_token'];
        $twitter_token->access_token_secret = $datas['TwitterToken']['access_token_secret'];
        $twitter_token->twitter_id  = $datas['TwitterToken']['user'];
        $twitter_token->save();


        // Get the token
        twitterApiToken::getToken($id);
        $redirectUrl = "hao/socialnetwork/twittertokens/update/".$id;
        return Backend::redirect($redirectUrl);
    }


    public function onQuite(){
        return Backend::redirect("hao/socialnetwork/twittertokens");
    }
}
