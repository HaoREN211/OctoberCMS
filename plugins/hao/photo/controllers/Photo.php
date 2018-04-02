<?php namespace Hao\Photo\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend;
use Hao\Photo\Classes\Photo as HaoPhoto;

/**
 * Photo Back-end Controller
 */
class Photo extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Photo', 'photo', 'photo');
    }


    /**
     * return to the photo main page
     * @return mixed
     */
    public function onCancel(){
        return Backend::redirect('hao/photo/index/index');
    }

    /**
     * @return mixed|string
     */
    public function onCreate(){
        $datas = post();
        if(is_array($datas) && array_key_exists('Photo', $datas)){
            $data_photo = $datas['Photo'];
            if(is_array($data_photo) && array_key_exists('url', $data_photo)){
                $photo = new HaoPhoto($data_photo['url']);
                return $photo->createNewPhotoFromUrl();
            }
        }
    }


    /**
     * Change a photo
     * @return mixed
     */
    public function onChange(){
        $photo = new HaoPhoto('no url');
        return $photo->getRandomPhoto();
    }
}
