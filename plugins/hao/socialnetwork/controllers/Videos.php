<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Facades\Backend;
use Hao\Socialnetwork\Classes\Video as HaoVideo;

/**
 * Videos Back-end Controller
 */
class Videos extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';


    /**
     * Videos constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Socialnetwork', 'socialnetwork', 'videos');
    }


    /**
     * @param null $id
     * @return mixed
     */
    public function onQuite($id = null){
        return Backend::redirect('hao/socialnetwork/videos');
    }



    public function onCreate(){
        $datas = post();
        $video = new HaoVideo($datas['Video']['url'],
            $datas['Video']['video_type'],
            $datas['Video']['name'],
            $datas['Video']['is_watched'],
            $datas['Video']['is_liked']);
        $video->getVideoUrl();
//        traceLog($video);
    }
}
