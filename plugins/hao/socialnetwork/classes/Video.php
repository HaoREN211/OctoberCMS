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
use Backend;

/**
 * Class Video
 * @package Hao\Socialnetwork\Classes
 */
class Video
{
    private $url =   null;
    private $videoType = null;
    private $name = null;
    private $id = null;

    /**
     * URL who saves the video.
     * @var null
     */
    private $videoUrl = null;

    /**
     * Video constructor.
     * @param null $url
     * @param null $videoType
     */
    public function __construct($url =null,
                                $videoType=null)
    {
        $this->url = $url;
        $this->videoType = $videoType;
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
        $title = $videoUrl->getInformation('/<title>(.*)<\/title>/');
        $this->videoUrl = $url;
        $this->name = $title;
    }


    /**
     *
     */
    public function saveVideo(){
        if($this->videoUrl != null && strlen( $this->videoUrl)>0){
            $count = (int)HaoVideo::where('name', $this->name)->count();
            if($count == (int)0){
                $video = HaoVideo::create([
                    'url' =>    $this->videoUrl,
                    'type' =>   $this->videoType,
                    'name'  =>  $this->name,
                    'source' => $this->url,
                    'is_liked'  =>  false,
                    'is_watched'    => false,
                ]);
                $this->id = $video->id;
            }
            else{
                $video = HaoVideo::where('name', $this->name)->first();
                $this->id = $video->id;
            }
        }
    }


    /**
     * @return null
     */
    public function redirectVideo(){
        if($this->id != null){
            return Backend::redirect('hao/socialnetwork/videos/update/'.$this->id);
        }
        else
            return null;
    }


    /**
     *
     * @param $id
     * @return null
     */
    public function synchronize($id){
        $this->setId($id);
        $this->getSource();
        $this->getVideoUrlXvideo();
        $video = HaoVideo::find($id);
        $video->url = $this->videoUrl;
        $video->save();
        return $this->redirectVideo();
    }

    /**
     * @param $id
     */
    private function setId($id){
        $this->id = $id;
    }


    /**
     * get source of page
     */
    private function getSource(){
        if($this->id != null && strlen($this->id)>0){
            $video = HaoVideo::find($this->id);
            $this->url = $video->source;
        }
    }
}
