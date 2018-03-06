<?php
namespace Hao\Socialnetwork\Classes\Instagram;

/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/6
 * Time: 22:25
 */

/**
 * Class InstagramGenarate
 */
abstract class InstagramGenarate implements Instagram
{
    /**
     * @param string $screenName
     * @return mixed
     */
    protected function getUserApiUrl(string $screenName){
        $resultat = str_replace('{username}',
            $screenName,
            $this::ACCOUNT_JSON_INFO);
        return $resultat;
    }
}
