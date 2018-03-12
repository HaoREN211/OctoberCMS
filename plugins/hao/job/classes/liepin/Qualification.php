<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 22:52
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinQualification as HaoQualification;

/**
 * Class Qualification
 * @package Hao\Job\Classes\Liepin
 */
class Qualification
{
    private $qualification = null;
    private $offer = null;

    /**
     * Qualification constructor.
     * @param $qualification
     */
    public function __construct($qualification, $offerId)
    {
        $this->qualification = $qualification;
        $this->offer = $offerId;
    }


    /**
     * @return bool
     */
    public function saveQualification(){
        if(is_array($this->qualification)){
            foreach ($this->qualification as $qualification){
                $this->saveBddQualification($qualification);
            }
        }

        return false;
    }

    /**
     * @param string $qualification
     * @return bool
     */
    private function existBdd(string $qualification){
        $count = (int)HaoQualification::where('name', $qualification)
            ->count();
        if($count>(int)0){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @param $qualification
     * @return bool
     */
    private function saveBddQualification($qualification)
    {
        if (!$this->existBdd($qualification)) {
            HaoQualification::create([
                'name' => $qualification
            ]);
            if ($this->existBdd($qualification))
                return true;
            else
                return false;
        }
        else
            return true;
    }
}
