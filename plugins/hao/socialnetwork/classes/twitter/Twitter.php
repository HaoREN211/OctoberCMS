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
use Hao\Socialnetwork\Models\Hashtag as HaoHashtag;
use Hao\Socialnetwork\Models\TwitterMedia as HaoMedia;
use Hao\Socialnetwork\Models\TwitterTweet as HaoTweet;
use Hao\Socialnetwork\Models\TwitterTweetHashtag as HaoTweetHashtag;
use Hao\Socialnetwork\Models\TwitterTweetMedia as HaoTweetMedia;
use Hao\Socialnetwork\Models\TwitterTweetMention as HaoTweetMention;


class Twitter
{
    private $token = "";
    private $header = "";

    const urlApiFollowerList = "https://api.twitter.com/1.1/followers/ids.json?cursor=-1&count=900&user_id=";
    const urlApiFriendList = "https://api.twitter.com/1.1/friends/ids.json?cursor=-1&count=900&user_id=";
    const urlApiUserShow = "https://api.twitter.com/1.1/users/show.json?user_id=";
    const urlApiUserShowByName = "https://api.twitter.com/1.1/users/show.json?screen_name=";
    const urlApiStatusesUserTimeline = "https://api.twitter.com/1.1/statuses/user_timeline.json?count=200&user_id=";
    const urlApiStatusesShow = "https://api.twitter.com/1.1/statuses/show.json?id=";

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
     * @param string $screenName
     * @return string
     */
    public function createNewUser(string $screenName){
        $newUser = $this->apiUserShowByName($screenName);
        $this->saveUserShow($newUser);
        if(property_exists($newUser, "id_str")){
            $idNewUser = $newUser->id_str;
            $count = (int)HaoTwitterUser::where('id', $idNewUser)->count();
            if($count>0){
                return (string)$idNewUser;
            }
            else
                return (string)0;
        }
        else
            return (string)0;
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


    public function synchronizationTweet($id){
        $response =  $this->apiStatusesUserTimeline($id);
        $iteration = (boolean)true;
        while($iteration){
            if((int)count($response)<(int)200){
                $iteration = (boolean)false;
            };

            foreach ($response as $tweet){
                $this->saveTweet($tweet);
            }
        }
    }

    private function saveTweet($tweet){
        trace_log($tweet->id_str);
        if(is_object($tweet) && property_exists($tweet, 'id_str')) {
            $tweet_count = (int)HaoTweet::where('id', $tweet->id_str)->count();
            if($tweet_count === (int)0) {
                $entities = $tweet->entities;
                $list_user_mentions = array();
                $list_hashtag = array();
                $list_media = array();

                if(is_object($entities)) {
                    // Save hashtag contained in the tweet
                    if(property_exists($entities, 'hashtags')) {
                        $hashtags = $tweet->entities->hashtags;
                        if (is_array($hashtags) && count($hashtags) > 0) {
                            foreach ($hashtags as $hashtag) {
                                $hashtagName = $hashtag->text;
                                $hashtagId = $this->saveHashtag($hashtagName);
                                array_push($list_hashtag, $hashtagId);
                            }
                        }
                    }

                    // Save medias contained in the tweet
                    if(property_exists($entities, 'media')) {
                        $medias = $tweet->entities->media;
                        if (is_array($medias) && count($medias) > 0) {
                            foreach ($medias as $media) {
                                $mediaId = $this->saveMedia($media);
                                array_push($list_media, $mediaId);
                            }
                        }
                    }

                    // Save medias contained in the tweet
                    if(property_exists($entities, 'user_mentions')) {
                        $user_mentions = $tweet->entities->user_mentions;
                        if (is_array($user_mentions) && count($user_mentions) > 0) {
                            foreach ($user_mentions as $user_mention) {
                                $userId = $this->saveUserById($user_mention->id_str);
                                if ($userId != null)
                                    array_push($list_user_mentions, $userId);
                            }
                        }
                    }
                }

                $in_reply_to_status_id = $this->saveTweetById($tweet->in_reply_to_status_id_str);
                $in_reply_to_user_id = $this->saveUserById($tweet->in_reply_to_user_id_str);
                $tweet_user_id = $this->saveUserById($tweet->user->id_str);

                $created_at = HaoDate::formatTime($tweet->created_at,
                    "D M d H:i:s O Y",
                    "yyyy-MM-dd HH:mm:ss");

                $possibly_sensitive = null;
                if(property_exists($tweet, 'possibly_sensitive'))
                    $possibly_sensitive = $tweet->possibly_sensitive;

                $geo = null;
                $place = null;

                if(is_object($tweet->geo)
                    && property_exists($tweet->geo, 'id')){
                    $geo = $tweet->geo->id;
                }

                if(is_object($tweet->place)
                    && property_exists($tweet->place, 'id')){
                    $place = $tweet->place->id;
                }
                HaoTweet::create([
                    'id' => $tweet->id_str,
                    'created_at' => $created_at,
                    'text' => $tweet->text,
                    'truncated' => $tweet->truncated,
                    'source' => $tweet->source,
                    'in_reply_to_status_id' => $in_reply_to_status_id,
                    'in_reply_to_user_id' => $in_reply_to_user_id,
                    'use_id' => $tweet_user_id,
                    'geo' => $geo,
                    'place' => $place,
                    'contributors' => $tweet->contributors,
                    'is_quote_status' => $tweet->truncated,
                    'retweet_count' => $tweet->retweet_count,
                    'favorite_count' => $tweet->favorite_count,
                    'favorited' => $tweet->favorited,
                    'retweeted' => $tweet->retweeted,
                    'possibly_sensitive' => $possibly_sensitive,
                    'lang' => $tweet->lang
                ]);

                $id_tweet = $tweet->id_str;

                foreach ($list_hashtag as $id_hashtag){
                    $count = (int)HaoTweetHashtag::where('hashtag_id', $id_hashtag)
                        ->where('tweet_id', $id_tweet)
                        ->count();
                    if($count === (int)0){
                        HaoTweetHashtag::create([
                            'hashtag_id'    =>  $id_hashtag,
                            'tweet_id'      =>  $id_tweet
                        ]);
                    }
                }

                foreach ($list_media as $id_media){
                    $count = (int)HaoTweetMedia::where('media_id', $id_media)
                        ->where('tweet_id', $id_tweet)
                        ->count();
                    if($count === (int)0){
                        HaoTweetMedia::create([
                            'media_id'    =>  $id_media,
                            'tweet_id'      =>  $id_tweet
                        ]);
                    }
                }

                foreach ($list_user_mentions as $id_user){
                    $count = (int)HaoTweetMention::where('user_id', $id_user)
                        ->where('tweet_id', $id_tweet)
                        ->count();
                    if($count === (int)0){
                        HaoTweetMention::create([
                            'user_id'    =>  $id_user,
                            'tweet_id'      =>  $id_tweet
                        ]);
                    }
                }

            }

            return $tweet->id_str;

        }
        else
            return null;
    }


    /**
     * Save the media
     * @param object $media
     * @return string
     */
    private function saveMedia($media){
        $id = $media->id_str;
        $count = (int)HaoMedia::where('id', (string)$id)->count();
        trace_log($id." - ".$count);
        if($count === (int)0){
            $source_user_id = null;
            $source_status_id = null;
            if(property_exists($media, 'source_user_id_str')){
                $source_user_id = $this->saveUserById((string)$media->source_user_id_str);
            }

            if(property_exists($media, 'source_user_id_str')){
                $source_status_id = $this->saveTweetById((string)$media->source_status_id_str);
            }

            HaoMedia::firstOrCreate([
                'id'    =>  (string)$id,
                'media_url' =>  (string)$media->media_url,
                'media_url_https' =>  (string)$media->media_url_https,
                'url' =>  (string)$media->url,
                'display_url' =>  (string)$media->display_url,
                'expanded_url' =>  (string)$media->expanded_url,
                'type' =>  (string)$media->type,
                'source_status_id' =>  $source_status_id,
                'source_user_id' =>  $source_user_id,
            ]);
        }
        return (string)$id;
    }


    /**
     * Save the twitter user by giving the id
     * @param string $id
     * @return null|string
     */
    private function saveUserById($id){
        if($id != null) {
            $count = (int)HaoTwitterUser::where('id', $id)->count();
            if ($count === (int)0) {
                $user = $this->apiUserShow($id);
                if (is_object($user) && property_exists($user, 'id_str')) {
                    return $id;
                } else
                    return null;
            } else {
                return $id;
            }
        }
        else
            return null;
    }

    /**
     * Save tweet by id
     * @param string $id
     * @return null|string
     */
    private function saveTweetById($id){
        if($id != null) {
            $count = (int)HaoTweet::where('id', $id)->count();
            if ($count === (int)0) {
                $tweet = $this->apiTweetShow($id);
                if (is_object($tweet) && property_exists($tweet, 'id_str')) {
                    $this->saveTweet($tweet);
                    return $id;
                } else
                    return null;
            } else
                return $id;
        }
        else
            return null;
    }


    /**
     * Get the information about a single tweet
     * @param $id
     * @return mixed|string
     */
    private function apiTweetShow($id){
        $url = $this::urlApiStatusesShow.$id;

        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }

    /**
     * Save the hashtag and return hashtag id
     * @param $hashtag
     * @return string
     */
    private function saveHashtag($hashtag){
        $count = (int)HaoHashtag::where('name', $hashtag)->count();
        if($count === (int)0){
            $newHashtag = HaoHashtag::create([
                'name'  =>  $hashtag
            ]);

            return (string)$newHashtag->id;
        }
        else
        {
            $existHashtag = HaoHashtag::where('name', $hashtag)->first();
            return (string)$existHashtag->id;
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


    /**
     * @param $id
     * @return mixed|string
     */
    private function apiStatusesUserTimeline($id){
        // Initiation of URL and Header
        $url = $this::urlApiStatusesUserTimeline.$id;

        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }


    /**
     * @param $id
     * @return mixed|string
     */
    private function apiUserShow($id){
        // Initiation of URL and Header
        $url = $this::urlApiUserShow.$id;

        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }


    private function apiUserShowByName(string $screenName){
        // Initiation of URL and Header
        $url = $this::urlApiUserShowByName.$screenName;

        // Get the response pf API.
        $reponse = HaoHttpRequest::httpGetResultat($url, $this->header);
        $reponse = gzdecode($reponse);
        $reponse = json_decode($reponse);
        return $reponse;
    }

    /**
     * @param string $site
     * @return bool|int|string
     */
    static public function getScreenNameFromUrl(string $site){
        if((ends_with($site, "\\") ||
            ends_with($site, "/"))
            && strlen($site)>2){
            $site = substr($site, 0, strlen($site)-2);
        }

        $group = explode("\\", $site);
        $group_length = count($group);
        $site = $group[$group_length-1];

        $group = explode("/", $site);
        $group_length = count($group);
        $site = $group[$group_length-1];

        return $site;
    }
}
