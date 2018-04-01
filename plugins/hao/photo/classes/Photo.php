<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/31
 * Time: 23:50
 */
namespace Hao\Photo\Classes;
use Hao\Socialnetwork\Classes\HttpRequest as HaoHttp;
use Hao\Photo\Models\Photo as HaoPhoto;
use Backend;

/**
 * Class Photo
 * @package Hao\Photo\Classes
 */
class Photo
{
    private $url = null;
    private $UUID = null;
    private $path = null;
    private $id = null;
    private $BDDpath = null;

    /**
     * Photo constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }


    /**
 * Verify if the url property is empty or not.
 * @return bool
 */
    private function verifyUrlNotEmpty(){
        if($this->url != null && strlen($this->url)>0)
            return true;
        else
            return false;
    }



    /**
     * Verify if the UUID property is empty or not.
     * @return bool
     */
    private function verifyUUIDNotEmpty(){
        if($this->UUID != null && strlen($this->UUID)>0)
            return true;
        else
            return false;
    }

    /**
     * Verify if the path property is empty or not.
     * @return bool
     */
    private function verifyPathNotEmpty(){
        if($this->path != null && strlen($this->path)>0)
            return true;
        else
            return false;
    }

    /**
     * Verify the properties url, path and UUID are all not empty of not
     * @return bool
     */
    private function verifyPropertyNotNull(){
        if($this->verifyUrlNotEmpty())
            if($this->verifyUUIDNotEmpty())
                if($this->verifyPathNotEmpty())
                    return true;

        return false;
    }


    /**
     * initialize UUID from the url
     */
    private function initializeUUID(){
        if($this->verifyUrlNotEmpty()){
            $this->UUID = uniqid();
        }
    }


    /**
     * initialize the path property
     */
    private function initializePath(){
        if($this->verifyUUIDNotEmpty()){
            if (!file_exists('./storage/app/media/photo')) {
                mkdir('./storage/app/media/photo', 0777, true);
            }

            $this->path = (string)'./storage/app/media/photo/'
                .(string)$this->UUID
                .(string)'.'
                .(string)$this->getPhotoSuffixe();

            $uuid = uniqid();
            $url_complet = Backend::url($uuid);
            $url_complet = str_replace('backend/'.$uuid, '', $url_complet);
            $url_complet = str_replace('index.php/', '', $url_complet);
            $url_complet = $url_complet.(string)'storage/app/media/photo/'
                .(string)$this->UUID
                .(string)'.'
                .(string)$this->getPhotoSuffixe();
            $this->BDDpath = $url_complet;
        }
    }


    /**
     * return the suffixe of the url
     * @return null
     */
    private function getPhotoSuffixe(){
        if($this->verifyUrlNotEmpty()){
            $groups = preg_split('/\./', $this->url);
            return $groups[count($groups)-1];
        }
        return null;
    }


    /**
     * verify if the url is not saved in the BDD
     * @return bool
     */
    private function notExistUrl(){
        (int)$count = (int)HaoPhoto::where('url', $this->url)
                        ->count();
        if($count === (int)0){
            return true;
        }
        else
            return false;
    }


    /**
     * Save the photo information
     */
    private function savePhoto(){
        if($this->verifyPropertyNotNull()) {
            if ($this->notExistUrl()) {
                $this->savePhotoFromUrlIntoLocal();
                $photo = HaoPhoto::create([
                    'url' => $this->url,
                    'UUID'  =>  $this->UUID,
                    'path'  =>  $this->BDDpath
                ]);

                return $photo;
            }
            else{
                return HaoPhoto::where('url', $this->url)->first();
            }
        }
    }

    /**
     * Save the picture into the local file
     */
    private function savePhotoFromUrlIntoLocal(){
        if($this->verifyPropertyNotNull()){
            $header = array();
            $photo_url = HaoHttp::httpGetResultat($this->url, $header);
            file_put_contents($this->path, $photo_url);
        }
    }


    /**
     * @return mixed|string
     */
    public function createNewPhotoFromUrl(){
        if($this->verifyUrlNotEmpty()){
            $this->initializeUUID();
            $this->initializePath();
        }


        $photo = $this->savePhoto();
        if(is_object($photo))
            return Backend::redirect('hao/photo/photo/update/'.$photo->id);

        return null;
    }
}
