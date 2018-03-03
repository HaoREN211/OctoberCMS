<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/3
 * Time: 10:10
 */

namespace Hao\Socialnetwork\Classes\Twitter;
use Hao\Socialnetwork\Models\TwitterToken;
use Hao\Socialnetwork\Classes\Base64encoder as HaoBase64Encodier;
use Hao\Socialnetwork\Classes\HttpRequest as HaoHttpRequest;
use Flash;
use Illuminate\Support\Facades\Lang;

/**
 * Class Token
 * @package Hao\Socialnetwork\Classes\Twitter
 */
class Token
{
    static function getToken($id){

        // Find record of TwitterToken whose id is $id
        $twitterToken = TwitterToken::find($id);
        $consumer_key   = $twitterToken->consumer_key;
        $consumer_secret = $twitterToken->consumer_secret;

        // encoded consumer key and consumer secret en base 64
        $base64_token = HaoBase64Encodier::get_base64_encoded_bearer_token_credentials(
            $consumer_key,
            $consumer_secret,
            "Twitter");

        // requeste body
        $fields = array(
            'grant_type'=>urlencode("client_credentials")
        );

        // requeste header
        $headers = array(
            'User-Agent: My Twitter App v1.0.23',
            'Authorization: Basic '.$base64_token,
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Content-Length: 29',
            'Accept-Encoding: gzip');

        // call the api and get token who is compresses
        $token = HaoHttpRequest::httpPostRequest(HaoHttpRequest::TwitterTokenUrl,
            $fields,
            $headers);

        // decompress the token and retrieve token and token type
        $resultat_decompress    = gzdecode ($token);
        $bearer_token_array     = (json_decode($resultat_decompress));
        $twitterToken->token    =   $bearer_token_array->access_token;
        $twitterToken->token_type     =   $bearer_token_array->token_type;
        $twitterToken->base64_encoded_token_credentials = $base64_token;
        $twitterToken->updated_at = time();

        $twitterToken->save();

        Flash::success(Lang::get('hao.socialnetwork::lang.form.twitter.token.synchronization'));
    }
}
