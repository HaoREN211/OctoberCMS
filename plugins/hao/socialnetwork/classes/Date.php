<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/3
 * Time: 14:48
 */

namespace Hao\Socialnetwork\Classes;

/**
 * Class Date
 * @package Hao\Socialnetwork\Classes
 */
class Date
{

    /**
     * @param $date
     * @param string $originalFormat
     * @param string $format
     * @return string
     */
    static function formatTime($date, string $originalFormat, string $format){

        // "D M d H:i:s O Y"
        if($originalFormat ===(string)"D M d H:i:s O Y") {
            $date = date_parse_from_format("D M d H:i:s O Y", $date);
            if (is_array($date)) {
                if ($format === (string)"yyyy-MM-dd HH:mm:ss") {
                    return $date['year'] .
                        "-" . $date['month'] .
                        "-" . $date['day'] .
                        " " . $date['hour'] .
                        ":" . $date['minute'] .
                        ":" . $date["second"];
                }
            }
        }
    }
}
