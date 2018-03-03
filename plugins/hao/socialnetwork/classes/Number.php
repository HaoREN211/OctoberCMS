<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/3
 * Time: 13:18
 */

namespace Hao\Socialnetwork\Classes;


class Number
{
    static public function NumToStr($num){
        // 查找字符串首次出现的位置（不区分大小写）
        if (stripos($num,'e')===false) return $num;

        //出现科学计数法，还原成字符串
        $num = trim(preg_replace('/[=\'"]/','',$num,1),'"');
        $result = "";
        while ($num > 0){
            $v = $num - floor($num / 10)*10;
            $num = floor($num / 10);
            $result   =   $v . $result;
        }
        return $result;
    }
}
