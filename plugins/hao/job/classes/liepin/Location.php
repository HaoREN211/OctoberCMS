<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/12
 * Time: 1:25
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinLocation as HaoLocation;

class Location
{
    private $name = null;

    /**
     * Location constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function save(){
        $result = $this->saveBdd();
        if($result){
            return $this->getIdByName();
        }
        else
            return null;
    }


    /**
     * @return mixed
     */
    private function getIdByName(){
        return HaoLocation::where('name', $this->name)->select('id')->first()->id;
    }


    /**
     * @param string $name
     * @return bool
     */
    private function existBdd(string $name){
        $count = (int)HaoLocation::where('name', $name)
            ->count();
        if($count>(int)0){
            return true;
        }
        else{
            return false;
        }
    }


    /**
     * @return bool
     */
    private function saveBdd()
    {
        $name = $this->name;
        if (!$this->existBdd($name)) {
            HaoLocation::create([
                'name' => $name
            ]);
            if ($this->existBdd($name))
                return true;
            else
                return false;
        }
        else
            return true;
    }
}
