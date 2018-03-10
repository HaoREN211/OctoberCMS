<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/9
 * Time: 23:28
 */

namespace Hao\Socialnetwork\Classes\Instagram;
use Hao\Socialnetwork\Classes\Instagram\InstagramGenarate as HaoInstagram;
use Hao\Socialnetwork\Classes\HttpRequest as HaoHttp;
use Hao\Socialnetwork\Classes\Instagram\User as HaoUser;
use Hao\Socialnetwork\Models\InstagramUser as HaoModelUser;


/**
 * Class Follower
 * @package Hao\Socialnetwork\Classes\Instagram
 */
class Follower extends HaoInstagram
{
    private $accountId = null;
    private $accountName = null;
    private $header = array();
    /**
     * Follower constructor.
     * @param $accountId
     */
    public function __construct($accountName)
    {
        $this->accountName = $accountName;
        if((int)HaoModelUser::where('username', $accountName)->count()===(int)1){
            $this->accountId = $user = HaoModelUser::where('username', $accountName)
                ->orderBy('id')
                ->first()
                ->id;
            trace_log($this);
        };
    }

    /**
     * @param $accountId
     * @param $count
     * @param string $after
     * @return mixed
     */
    public function getFollowingJsonLink($count=10, $after)
    {
        $accountId = $this->accountId;

        $url = str_replace('{{accountId}}', urlencode($accountId), static::FOLLOWING_URL);
        $url = str_replace('{{count}}', urlencode($count), $url);
        if ($after === '') {
            $url = str_replace('&after={{after}}', '', $url);
        } else {
            $url = str_replace('{{after}}', urlencode($after), $url);
        }
        return $url;
    }


    public function synchronizationFollower(){
        $after = '';
        $url = $this->getFollowingJsonLink(10,$after);
        trace_log('url: '.$url);
        $response = HaoHttp::httpGetResultat($url, $this->header);
        trace_log('response: '.$response);
    }
}
