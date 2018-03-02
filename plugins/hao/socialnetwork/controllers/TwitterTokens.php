<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Hao\Socialnetwork\Models\TwitterToken;
use Hao\Socialnetwork\Classes\encoder;

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
        $datas = post();
        $consumer_key = $datas['TwitterToken']['consumer_key'];
        $consumer_secret = $datas['TwitterToken']['consumer_secret'];
        $access_token = $datas['TwitterToken']['access_token'];
        $access_token_secret = $datas['TwitterToken']['access_token_secret'];

        $twitter_token = TwitterToken::find($id);
        $base64_encoded_bearer_token = encoder::get_base64_encoded_bearer_token_credentials($consumer_key, $consumer_secret);
        trace_log($base64_encoded_bearer_token);
//        trace_log($twitter_token);
    }
}
