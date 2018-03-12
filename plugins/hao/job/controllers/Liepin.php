<?php namespace Hao\Job\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lang;
use Hao\Job\Classes\Liepin as HaoOffer;

/**
 * Liepin Back-end Controller
 */
class Liepin extends Controller
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

        BackendMenu::setContext('Hao.Job', 'job', 'liepin');
    }

    public function create(){
        $this->pageTitle=Lang::get('hao.job::lang.liepin.menu.create');
    }


    /**
     * Used in the new offer fields for creating a new offer
     * @return mixed
     */
    public function onReturnMainPage(){
        return Backend::redirect('hao/job/index/index');
    }

    public function onSaveOffer(){
        $datas = post();
        if(is_array($datas) && array_key_exists('userUrl', $datas)){
            $url = $datas['userUrl'];
            $offer = new HaoOffer((string)$url);
            $offer->getUrlContent();
            $offer->saveLiepinOffer();
        }
    }
}
