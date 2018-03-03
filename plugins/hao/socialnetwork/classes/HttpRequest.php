<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/3
 * Time: 10:16
 */

namespace Hao\Socialnetwork\Classes;
use http\Exception\InvalidArgumentException;

/**
 * Class HttpRequest
 * @package Hao\Socialnetwork\Classes
 */
class HttpRequest
{
    const TwitterTokenUrl = "https://api.twitter.com/oauth2/token";

    /**
     * @param $url
     * @param $fields
     * @param $headers
     * @return mixed
     */
    static function httpPostRequest($url, $fields, $headers){
        // http://php.net/manual/fr/function.curl-setopt.php
        //open connection

        if($url === null)
            throw new InvalidArgumentException('Invalided URL');

        $ch = curl_init();

        $fields_string = (is_array($fields)) ? http_build_query($fields) : $fields;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        return $result;
    }


    /**
     * @param $url
     * @param $headers
     * @return mixed
     */
    static function httpGetResultat($url, $headers){
        if($url === null)
            throw new InvalidArgumentException('Invalided URL');
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        return $result;
    }
}
