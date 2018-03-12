<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 10:48
 */

namespace Hao\Job\Classes;
use October\Rain\Exception\ApplicationException;

/**
 * Class Regularexpression
 * @package Hao\Job\Classes
 */
class Regularexpression
{
    /**
     * $Content from which we want to retrieve the information
     * @var
     */
    private $content;

    /**
     * Regularexpression constructor.
     * @param null $content
     */
    public function __construct($content=null)
    {
        $this->content = $content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(string $content=null){
        $this->content = $content;
    }

    /**
     * @param string $pattern
     * @return mixed
     * @throws ApplicationException
     */
    public function getInformation(string $pattern){
        if($this->content == null)
            throw new ApplicationException('You must, first of all, retrieve the content');

        $matched = preg_match($pattern,$this->content, $matches);

        if($matched)
            return trim($matches[1]);
        else
            return null;
    }

    /**
     * @param string $pattern
     * @return array
     * @throws ApplicationException
     */
    public function getAllInformation(string $pattern){
        if($this->content == null)
            throw new ApplicationException('You must, first of all, retrieve the content');

        $matched = preg_match_all($pattern,$this->content, $matches);
        $result = array();
        if($matched){
            $results = $matches[1];
            foreach ($results as $match){
                array_push($result, $match);
            }
            return $result;
        }
        else
            return $result;
    }

    /**
     * @param string $pattern
     * @param string $content
     * @return mixed
     */
    public function getInformationFromContent(string $pattern, string $content){
        preg_match($pattern,$content, $matches);
        return trim($matches[1]);
    }

    /**
     * @param string $pattern
     * @param string $content
     * @return array
     */
    public function getAllInformationFromContent(string $pattern, string $content){
        $matched = preg_match_all($pattern,$content, $matches);
        $result = array();
        if($matched){
            $results = $matches[1];
            foreach ($results as $match){
                array_push($result, $match);
            }
        }
        return $result;
    }
}
