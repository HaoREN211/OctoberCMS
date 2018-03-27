<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/27
 * Time: 23:08
 */

namespace Hao\Socialnetwork\Classes;
use Hao\Socialnetwork\Models\Video as HaoVideo;
use Hao\Socialnetwork\Classes\HttpRequest as HaoHttp;
use Hao\Job\Classes\Regularexpression as HaoRegPre;

/**
 * Class Video
 * @package Hao\Socialnetwork\Classes
 */
class Video
{
    private $url =   null;
    private $videoType = null;
    private $name = null;
    private $is_watched = null;
    private $is_liked = null;


    /**
     * URL who saves the video.
     * @var null
     */
    private $videoUrl = null;

    /**
     * Video constructor.
     * @param null $url
     * @param null $videoType
     * @param null $name
     * @param null $is_watched
     * @param null $is_liked
     */
    public function __construct($url =null,
                                $videoType=null,
                                $name = null,
                                $is_watched = null,
                                $is_liked=null)
    {
        $this->url = $url;
        $this->videoType = $videoType;
        $this->name = $name;
        $this->is_watched = $is_watched;
        $this->is_liked = $is_liked;
    }


    /**
     *
     */
    public function getVideoUrl(){
        if($this->videoType == 1)
            $this->getVideoUrlXvideo();

    }


    /**
     *
     */
    private function getVideoUrlXvideo(){
        $header = array();
        $reponse = HaoHttp::httpGetResultat($this->url, $header);
        $videoUrl = new HaoRegPre($reponse);
        $url = $videoUrl->getInformation('/setVideoUrlHigh\(\'([^)]+)\'\)/');
        $this->videoUrl = $url;
    }
}
