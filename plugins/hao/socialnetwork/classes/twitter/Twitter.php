<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/3
 * Time: 12:04
 */

namespace Hao\Socialnetwork\Classes\Twitter;
use Hao\Socialnetwork\Models\TwitterToken as HaoTwitterToken;
use Hao\Socialnetwork\Classes\HttpRequest as HaoHttpRequest;
use Hao\Socialnetwork\Classes\Number as HaoNumber;
use Hao\Socialnetwork\Models\TwitterUser as HaoTwitterUser;
use Hao\Socialnetwork\Models\TwitterProfileLocation as HaoProfileLocation;
use Hao\Socialnetwork\Classes\Date as HaoDate;
use Hao\Socialnetwork\Models\TwitterFollower as HaoFollower;


class Twitter
{
    private $token = "";
    private $header = "";

    const urlApiFollowerList = "https://api.twitter.com/1.1/followers/ids.json?cursor=-1&count=900&user_id=";
    const urlApiFriendList = "https://api.twitter.com/1.1/friends/ids.json?cursor=-1&count=900&user_id=";
    const urlApiUserShow = "https://api.twitter.com/1.1/users/show.json?user_id=";

    /**
     * Twitter constructor.
     */
    public function __construct()
    {
        $twitter = HaoTwitterToken::where('id', '>', '0')
            ->orderBy('updated_at', 'desc')
            ->first();
        $this->token = $twitter->token;
        $this->header = array(
            'User-Agent: My Twitter App v1.0.23',
            'Authorization: Bearer '.$twitter->token,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept-Encoding: gzip');
    }


    /**
     * synchronization the list of friend
     * @param $id
     */
    public function synchronizationFriend($id){
        $friend_list = $this->apiFriendList($id);
        $friends_ids = $friend_list->ids;

        foreach ($friends_ids as $friend){
            $friend_id = HaoNumber::NumToStr($friend);
            $count = (int)HaoTwitterUser::where('id', $friend_id)->count();
            if((int)$count === (int)0){
                $friend_show = $this->apiUserShow($friend_id);
                $this->saveUserShow($friend_show);
            }
            $this->saveFollower($friend_id, $id);
        }
    }


    /**
     * Synchronization of followers
     * @param $id
     */
    public function synchronizationFollower($id){
        $follower_list = $this->apiFollowerList($id);
        $follower_ids = $follower_list->ids;

        foreach ($follower_ids as $follower){
            $follower_id = HaoNumber::NumToStr($follower);
            $count = (int)HaoTwitterUser::where('id', $follower_id)->count();
            if((int)$count === (int)0){
                $follower_show = $this->apiUserShow($follower_id);
                $this->saveUserShow($follower_show);
            }
            $this->saveFollower($id, $follower_id);
        }
    }


    /** Save the relation between user and follower
     * @param $user_id
     * @param $follower_id
     */
    private function saveFollower($user_id, $follower_id){
        $countUser = (int)HaoTwitterUser::where('id', $user_id)->count();
        if($countUser === (int)1){
            $countFollower = (int)HaoTwitterUser::where('id', $follower_id)->count();
            if($countFollower === (int)1){
                $countRelation = HaoFollower::where('user_id', $user_id)
                    ->where('follower_id', $follower_id)
                    ->count();

                if($countRelation === (int)0){
                    HaoFollower::create([
                        'user_id'   =>      $user_id,
                        'follower_id'   =>  $follower_id
                    ]);
                }
            }
        }
    }

    /**
     * Save the user information
     * @param $response
     */
    private function saveUserShow($response){
        if(property_exists($response, 'id_str')){

            // Save the profile location in the user information
            $profile_location = $response->profile_location;
            $profile_location_id = $this->SaveUserProfileLocation($profile_location);

            $count_user = HaoTwitterUser::where('id', $response->id_str)->count();
            if((int)$count_user === (int)0){

                $created_at = HaoDate::formatTime($response->created_at,
                    "D M d H:i:s O Y",
                    "yyyy-MM-dd HH:mm:ss");

                HaoTwitterUser::create([
                    "id"                =>  $response->id_str,
                    "name"              =>  $response->name,
                    "screen_name"       =>  $response->screen_name,
                    "location"          =>  $response->location,
                    "profile_location_id"  =>  $profile_location_id,
                    "description"       =>  $response->description,
                    "url"               =>  $response->url,
                    "protected"         =>  $response->protected,
                    "followers_count"   =>  $response->followers_count,
                    "friends_count"     =>  $response->friends_count,
                    "listed_count"      =>  $response->listed_count,
                    "created_at"        =>  $created_at,
                    "favourites_count"  =>  $response->favourites_count,
                    "utc_offset"        =>  $response->utc_offset,
                    "time_zone"         =>  $response->time_zone,
                    "geo_enabled"       =>  $response->geo_enabled,
                    "verified"          =>  $response->verified,
                    "statuses_count"    =>  $response->statuses_count,
                    "lang"              =>  $response->lang,
                    "contributors_enabled"      =>  $response->contributors_enabled,
                    "is_translator"     =>  $response->is_translator,
                    "is_translation_enabled"    =>  $response->is_translation_enabled,
                    "profile_background_color"  =>  $response->profile_background_color,
                    "profile_background_image_url"  =>  $response->profile_background_image_url,
                    "profile_background_image_url_https"    =>  $response->profile_background_image_url_https,
                    "profile_background_tile"   =>  $response->profile_background_tile,
                    "profile_image_url"         =>  $response->profile_image_url,
                    "profile_image_url_https"   =>  $response->profile_image_url_https,
                    "profile_link_color"        =>  $response->profile_link_color,
                    "profile_sidebar_border_color"  =>  $response->profile_sidebar_border_color,
                    "profile_sidebar_fill_color"    =>  $response->profile_sidebar_fill_color,
                    "profile_text_color"        =>  $response->profile_text_color,
                    "profile_use_background_image"  =>  $response->profile_use_background_image,
                    "has_extended_profile"      =>  $response->has_extended_profile,
                    "default_profile"           =>  $response->default_profile,
                    "default_profile_image"     =>  $response->default_profile_image,
                    "following"                 =>  $response->following,
                    "follow_request_sent"       =>  $response->follow_request_sent,
                    "notifications"             =>  $response->notifications,
                    "translator_type"           =>  $response->translator_type,
                    "API"                       =>  "user_show"
                ]);
            }
        }
    }


    /**
     * Save the profile location of the user
     * And return id of profile location
     * @param $profile_location
     * @return null|string
     */
    private function SaveUserProfileLocation($profile_location){
        if($profile_location!=null
            && is_object($profile_location)
            && property_exists($profile_location, 'id')
            && $profile_location->id != null
            && $profile_location->id != ""){
            $count_profile = HaoProfileLocation::where('id', $profile_location->id)->count();
            if((int)$count_profile === (int)0){
                HaoProfileLocation::create([
                    "id"            =>  (string)$profile_location->id,
                    "url"           =>  "\"".(string)$profile_location->url."\"",
                    "place_type"    =>  (string)$profile_location->place_type,
                    "name"          =>  (string)$profile_location->name,
                    "full_name"     =>  (string)$profile_location->full_name,
                    "country_code"  =>  (string)$profile_location->country_code,
                    "country"       =>  (string)$profile_location->country,
                    "contained_within"  =>  (string)var_export($profile_location->contained_within, true),
                    "bounding_box"  =>  (string)$profile_location->bounding_box,
                    "attributes"    =>  (string)var_export($profile_location->attributes, true),
                    "API"           => "user_show",
                ]);
                return (string)$profile_location->id;
            }
            else
                return null;
        }
    }

    /**
     * Call the follower list api for getting the list of followers
     * @param $id
     * @return mixed|string
     */
    private function apiFollowerList($id){
        // Initiation of URL and Header
        $url = $this::urlApiFollowerList.$id;


        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }


    /**
     * get list of friend
     * @param $id
     * @return mixed|string
     */
    private function apiFriendList($id){
        // Initiation of URL and Header
        $url = $this::urlApiFriendList.$id;

        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }

    private function apiUserShow($id){
        // Initiation of URL and Header
        $url = $this::urlApiUserShow.$id;

        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }
}
