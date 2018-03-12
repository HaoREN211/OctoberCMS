<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 23:42
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinJobdetail as HaoJob;

/**
 * Class Jobdetail
 * @package Hao\Job\Classes\Liepin
 */
class Jobdetail
{
    private $detail = null;
    private $offer = null;

    /**
     * Jobdetail constructor.
     * @param $detail
     * @param $offer
     */
    public function __construct($detail, $offer)
    {
        $this->detail = $detail;
        $this->offer = $offer;
    }

    /**
     * @return bool
     */
    public function saveJobdetail(){
        if(is_array($this->detail)){
            foreach ($this->detail as $detail){
                $this->saveBddJobdetail($detail);
            }
        }

        return false;
    }

    /**
     * @param string $detail
     * @return bool
     */
    private function existBdd(string $detail){
        $count = (int)HaoJob::where('name', $detail)
            ->count();
        if($count>(int)0){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @param $detail
     * @return bool
     */
    private function saveBddJobdetail($detail)
    {
        if (!$this->existBdd($detail)) {
            HaoJob::create([
                'name' => $detail
            ]);
            if ($this->existBdd($detail))
                return true;
            else
                return false;
        }
        else
            return true;
    }
}
