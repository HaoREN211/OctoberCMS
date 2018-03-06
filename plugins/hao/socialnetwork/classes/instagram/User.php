<?php
namespace Hao\Socialnetwork\Classes\Instagram;

/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/6
 * Time: 22:22
 */

use Hao\Socialnetwork\Classes\HttpRequest as HaoHttp;
use Hao\Socialnetwork\Classes\Chaine as HaoChaine;
use Hao\Socialnetwork\Models\InstagramUser as HaoUser;

class User extends InstagramGenarate
{
    private $screenName = null;

    /**
     * InstagramUser constructor.
     * @param string $screenName
     */
    public function __construct(string $screenName)
    {
        $this->screenName = $screenName;
    }


    /**
     * @return null
     */
    public function saveUser(){
        $count = (int)HaoUser::where('username', $this->screenName)->count();
        if($count === (int)0){
            $response = $this->getUser();
            if($response == null)
                return null;
            else{
                $user = HaoChaine::extractObjectAtrribut($response, 'user');
                if($user == null)
                    return null;
                $id = HaoChaine::extractObjectAtrribut($user, 'id');
                if($id == null)
                    return null;
                $followed_by = HaoChaine::extractObjectAtrribut($user, 'followed_by');
                $follows = HaoChaine::extractObjectAtrribut($user, 'follows');

                $followed_by = HaoChaine::extractObjectAtrribut($followed_by, 'count');
                $follows = HaoChaine::extractObjectAtrribut($follows, 'count');


                HaoUser::create([
                    'biography' =>  HaoChaine::extractObjectAtrribut($user, 'biography'),
                    'blocked_by_viewer' =>  HaoChaine::extractObjectAtrribut($user, 'blocked_by_viewer'),
                    'country_block' =>  HaoChaine::extractObjectAtrribut($user, 'country_block'),
                    'external_url' =>  HaoChaine::extractObjectAtrribut($user, 'external_url'),
                    'external_url_linkshimmed' =>  HaoChaine::extractObjectAtrribut($user, 'external_url_linkshimmed'),
                    'followed_by' =>  $followed_by,
                    'followed_by_viewer' =>  HaoChaine::extractObjectAtrribut($user, 'followed_by_viewer'),
                    'follows' =>  $follows,
                    'follows_viewer' =>  HaoChaine::extractObjectAtrribut($user, 'follows_viewer'),
                    'full_name' =>  HaoChaine::extractObjectAtrribut($user, 'full_name'),
                    'has_blocked_viewer' =>  HaoChaine::extractObjectAtrribut($user, 'has_blocked_viewer'),
                    'has_requested_viewer' =>  HaoChaine::extractObjectAtrribut($user, 'has_requested_viewer'),
                    'id' =>  HaoChaine::extractObjectAtrribut($user, 'id'),
                    'is_private' =>  HaoChaine::extractObjectAtrribut($user, 'is_private'),
                    'is_verified' =>  HaoChaine::extractObjectAtrribut($user, 'is_verified'),
                    'profile_pic_url' =>  HaoChaine::extractObjectAtrribut($user, 'profile_pic_url'),
                    'profile_pic_url_hd' =>  HaoChaine::extractObjectAtrribut($user, 'profile_pic_url_hd'),
                    'requested_by_viewer' =>  HaoChaine::extractObjectAtrribut($user, 'requested_by_viewer'),
                    'username' =>  HaoChaine::extractObjectAtrribut($user, 'username'),
                    'connected_fb_page' =>  HaoChaine::extractObjectAtrribut($user, 'connected_fb_page'),
                ]);

                $count = (int)HaoUser::where('username', $this->screenName)->count();
                if($count === (int)1)
                    return $id;
                else
                    return null;
            }
        }
        else{
            $instagram = HaoUser::where('username', $this->screenName)->first();
            return $instagram->id;
        }
    }



    /**
     * Get user information via API
     * @return mixed|null
     */
    private function getUser(){
        $url = $this->getUserApiUrl($this->screenName);
        $header = array();
        $response = HaoHttp::httpGetResultat($url, $header);
        if(HaoChaine::isJson($response))
        {
            return json_decode($response);
        }
        return null;
    }
}
