<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/6
 * Time: 23:00
 */

namespace Hao\Socialnetwork\Classes;

/**
 * Class Chaine
 * @package Hao\Socialnetwork\Classes
 */
class Chaine
{
    /**
     * @param $jsonField
     * @return bool
     */
    static public function isJson($jsonField){
        json_decode($jsonField);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    /**
     * @param $object
     * @param string $attribut
     * @return null
     */
    static public function extractObjectAtrribut($object, string $attribut){
        if(is_object($object)){
            if(property_exists($object, $attribut)){
                $resultat =  $object->$attribut;
                if($resultat == "")
                    return null;
                else
                    return $resultat;
            }
        }
        return null;
    }
}
