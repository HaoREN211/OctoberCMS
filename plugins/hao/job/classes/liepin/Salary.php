<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/12
 * Time: 1:09
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinSalary as HaoSalary;


class Salary
{
    private $name = null;
    private $from = null;
    private $to = null;

    /**
     * Salary constructor.
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
        $this->getFrom();
        $this->getTo();

        $result = $this->saveBdd();
        if($result){
            return $this->getIdByName();
        }
        else
            return null;
    }

    /**
     * @return null
     */
    private function getFrom(){
        $matched = preg_match('/([0-9]+)-([0-9]+)/',
            $this->name,
            $matches);
        if($matched){
            $this->from = $matches[1];
            return $this->from;
        }
        else
            return null;
    }

    /**
     * @return null
     */
    private function getTo(){
        $matched = preg_match('/([0-9]+)-([0-9]+)/',
            $this->name,
            $matches);
        if($matched){
            $this->to = $matches[2];
            return $this->to;
        }
        else
            return null;
    }


    /**
     * @return mixed
     */
    private function getIdByName(){
        return HaoSalary::where('name', $this->name)->select('id')->first()->id;
    }


    /**
     * @param string $name
     * @return bool
     */
    private function existBdd(string $name){
        $count = (int)HaoSalary::where('name', $name)
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
            HaoSalary::create([
                'name' => $name,
                'from'  =>  $this->from,
                'to'    => $this->to
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
