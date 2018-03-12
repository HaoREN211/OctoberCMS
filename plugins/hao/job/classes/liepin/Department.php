<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/12
 * Time: 0:26
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinDepartment as HaoDep;

/**
 * Class Department
 * @package Hao\Job\Classes\Liepin
 */
class Department
{
    private $department = null;


    /**
     * Department constructor.
     * @param $department
     */
    public function __construct($department)
    {
        $this->department = $department;
    }

    /**
     *
     */
    public function saveDepartment(){
        $result = $this->saveBddDepartment();
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
        return HaoDep::where('name', $this->department)->select('id')->first()->id;
    }

    /**
     * @param string $department
     * @return bool
     */
    private function existBdd(string $department){
        $count = (int)HaoDep::where('name', $department)
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
    private function saveBddDepartment()
    {
        $department = $this->department;
        if (!$this->existBdd($department)) {
            HaoDep::create([
                'name' => $department
            ]);
            if ($this->existBdd($department))
                return true;
            else
                return false;
        }
        else
            return true;
    }
}
