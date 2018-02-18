<?php namespace Hao\Dictionary\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use October\Rain\Support\Facades\Flash;


/**
 * Learn Vocabulary Back-end Controller
 */
class LearnVocabulary extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
    ];

    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Dictionary', 'dictionary', 'learnvocabulary');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $list_language = array();
        $langues = DB::table("hao_dictionary_languages")->get();
        foreach ($langues as $langu){
            array_push($list_language, $langu);
        }
        $this->vars["languages"] = $list_language;
        $this->pageTitle = Lang::get('hao.dictionary::lang.plugin.menus.learnvocabulary');
    }

    public function onLearn()
    {
        $data = post();
        if(array_key_exists('language', $data)) {
            Flash::success($data['language']);
        }
    }
}
