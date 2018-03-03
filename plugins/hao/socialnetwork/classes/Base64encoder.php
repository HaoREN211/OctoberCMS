<?php namespace Hao\Socialnetwork\Classes;
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/3
 * Time: 0:17
 */

class Base64encoder
{
    /**
     * @param $key
     * @param $secret
     * @param $socialnetwork
     * @return string
     */
    static public function get_base64_encoded_bearer_token_credentials($key, $secret, $socialnetwork){
        if($key === null)
            throw new InvalidArgumentException('Invalided consumer account or key');
        if($secret === null)
            throw new InvalidArgumentException('Invalided consumer key secret or password');
        if($socialnetwork === (string)"Twitter"){
            $bearer_token_credentials = $key . ":" . $secret;
            $base64_encoded_bearer_token_credentials = base64_encode($bearer_token_credentials);
            return $base64_encoded_bearer_token_credentials;
        }
    }
}
