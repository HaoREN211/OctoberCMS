<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 10:32
 */

namespace Hao\Job\Classes;
use Hao\Socialnetwork\Classes\HttpRequest as HaoHttpRequest;
use Hao\Job\Classes\Regularexpression as HaoRegExp;

/**
 * Class Offer
 * @package Hao\Job\Classes
 */
abstract class Offer
{
    protected $offerUrl = null;
    protected $urlPage = null;
    protected $header = array();


    /**
     * Offer constructor.
     * @param string $OfferUrl
     */
    public function __construct(string $OfferUrl)
    {
        $this->offerUrl = $OfferUrl;
    }


    /**
     *
     */
    public function getUrlContent()
    {
        $response = HaoHttpRequest::httpGetResultat($this->offerUrl, $this->header);
        $this->urlPage = new HaoRegExp($response);
    }

    /**
     * @param string $pattern
     * @return mixed
     */
    public function getInformation(string $pattern){
        return $this->urlPage->getInformation($pattern);
    }

    /**
     * @param string $pattern
     * @return mixed
     */
    public function getAllInformation(string $pattern){
        return $this->urlPage->getAllInformation($pattern);
    }


    /**
     * @param string $pattern
     * @param string $content
     * @return mixed
     */
    public function getInformationFromContent(string $pattern, string $content){
        return $this->urlPage->getInformationFromContent($pattern, $content);
    }

    /**
     * @param string $pattern
     * @param string $content
     * @return mixed
     */
    public function getAllInformationFromContent(string $pattern, string $content){
        return $this->urlPage->getAllInformationFromContent($pattern, $content);
    }
}
