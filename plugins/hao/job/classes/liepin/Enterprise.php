<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 21:59
 */

namespace Hao\Job\Classes\Liepin;
use Hao\Job\Models\LiepinEnterprise as HaoEnterprise;

/**
 * Class Enterprise
 * @package Hao\Job\Classes\Liepin
 */
class Enterprise
{
    private $enterprise = null;
    private $description = null;


    /**
     * Enterprise constructor.
     * @param string $enterprise
     * @param string $description
     */
    public function __construct(string $enterprise,
                                string $description)
    {
        $this->enterprise = $enterprise;
        $this->description = $description;
    }

    public function saveEnterprise(){
        $enterpriseName = $this->getEnterpriseName();
        $url = $this->getUrl();
        $id = $this->getId();
        $containUrl = $this->containsUrl();
        if($this->existBdd($containUrl, $id, $enterpriseName)){
            if($containUrl){
                return $id;
            }
            else
            {
                return $this->getIdByName($enterpriseName);
            }
        }
        else{
            HaoEnterprise::create([
                'id'    =>  $id,
                'name'  =>  $enterpriseName,
                'URL'   =>  $url,
                'description'   =>  $this->description,
            ]);

            if($this->existBdd($containUrl, $id, $enterpriseName)){
                return $id;
            }
            else
                return null;
        }
    }

    /**
     * @param $containUrl
     * @param $id
     * @param $enterpriseName
     * @return bool
     */
    private function existBdd($containUrl,
                              $id,
                              $enterpriseName){
        if($this->containsUrl()){
            return $this->existBddById($id);
        }
        else{
            return $this->existBddByName($id);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    private function existBddById($id){
        $count = (int)HaoEnterprise::where('id', $id)->count();
        if($count > (int)0){
            return true;
        }
        else
            return false;
    }


    /**
     * @param $name
     * @return bool
     */
    private function existBddByName($name){
        $count = (int)HaoEnterprise::where('name', $name)->count();
        if($count > (int)0){
            return true;
        }
        else
            return false;
    }

    /**
     * @param $name
     * @return mixed
     */
    private function getIdByName($name){
        return HaoEnterprise::where('name', $name)->select('id')->first();
    }

    /**
     * @return bool
     */
    private function containsUrl(){
        if(strpos($this->enterprise, '</a>')){
            return (boolean)true;
        }
        else
        {
            return (boolean)false;
        }
    }


    /**
     * @return null|string
     */
    private function getEnterpriseName(){
        if($this->containsUrl()){
            $matched = preg_match_all('/>([^<]+)</',
                $this->enterprise,
                $match);
            if($matched){
                return trim($match[1][0]);
            }
        }

        return $this->enterprise;
    }


    /**
     * @return null|string
     */
    private function getUrl(){
        if($this->containsUrl()){
            $matched = preg_match_all('/href="([^"]+)"/',
                $this->enterprise,
                $match);
            if($matched){
                return trim($match[1][0]);
            }
        }

        return null;
    }


    /**
     * @return null|string
     */
    private function getId(){
        if($this->containsUrl()){
            $matched = preg_match_all('/\/([0-9]+)\//',
                $this->enterprise,
                $match);
            if($matched){
                return trim($match[1][0]);
            }
        }

        $count = (int)HaoEnterprise::where('id'<100000)
            ->count();
        if($count === (int)0){
            return 1;
        }
        else{
            $maxId = HaoEnterprise::where('id'<100000)
                ->max('id');
            return $maxId + (int)1;
        }

    }

}
