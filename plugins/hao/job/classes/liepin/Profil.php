<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/12
 * Time: 0:17
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinProfil as HaoProfil;

/**
 * Class Profil
 * @package Hao\Job\Classes\Liepin
 */
class Profil
{
    private $profil = null;
    private $offer = null;

    /**
     * Profil constructor.
     * @param $profil
     * @param $offer
     */
    public function __construct($profil, $offer)
    {
        $this->profil = $profil;
        $this->offer = $offer;
    }

    /**
     * @return bool
     */
    public function saveProfil(){
        if(is_array($this->profil)){
            foreach ($this->profil as $profil){
                $this->saveBddProfil($profil);
            }
        }

        return false;
    }


    /**
     * @param string $profil
     * @return bool
     */
    private function existBdd(string $profil){
        $count = (int)HaoProfil::where('name', $profil)
            ->count();
        if($count>(int)0){
            return true;
        }
        else{
            return false;
        }
    }


    /**
     * @param $profil
     * @return bool
     */
    private function saveBddProfil($profil)
    {
        if (!$this->existBdd($profil)) {
            HaoProfil::create([
                'name' => $profil
            ]);
            if ($this->existBdd($profil))
                return true;
            else
                return false;
        }
        else
            return true;
    }
}
