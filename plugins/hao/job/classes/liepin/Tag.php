<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 23:12
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinTag as HaoTag;

/**
 * Class Tag
 * @package Hao\Job\Classes\Liepin
 */
class Tag
{
    private $tag = null;
    private $offer = null;

    /**
     * Tag constructor.
     * @param $tag
     */
    public function __construct($tag, $offer)
    {
        $this->tag = $tag;
        $this->offer = $offer;
    }

    /**
     * @return bool
     */
    public function saveTag(){
        if(is_array($this->tag)){
            foreach ($this->tag as $tag){
                $this->saveBddTag($tag);
            }
        }

        return false;
    }


    /**
     * @param string $tag
     * @return bool
     */
    private function existBdd(string $tag){
        $count = (int)HaoTag::where('name', $tag)
            ->count();
        if($count>(int)0){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @param $tag
     * @return bool
     */
    private function saveBddTag($tag)
    {
        if (!$this->existBdd($tag)) {
            HaoTag::create([
                'name' => $tag
            ]);
            if ($this->existBdd($tag))
                return true;
            else
                return false;
        }
        else
            return true;
    }
}
