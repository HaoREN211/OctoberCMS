<?php namespace Hao\Dictionary\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

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
     * List all language
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


    /**
     * Set le language
     * @return mixed
     */
    public function onLearn()
    {
        $data = post();
        if(array_key_exists('language', $data)) {
            $language = $data['language'];
            Session::put('language', $language);
        }

        if(Session::has("language")){
            return Redirect::to("backend/hao/dictionary/learnvocabulary/learning");
        }
    }


    /**
     *
     */
    public function learning(){
        $this->pageTitle = Lang::get('hao.dictionary::lang.plugin.learning.page');
        if(!Session::has("vocabulary")){
            Session::put('vocabulary', 0);
        }

        if(Session::has('language') && Session::has('vocabulary')){
            $vocabulary_id = Session::get('vocabulary');
            $language_id   = Session::get('language');

            $nb_vocabulary =Db::table("hao_dictionary_vocabularies")
                ->where('language_id', '=', $language_id)
                ->where('id', '<>', $vocabulary_id)
                ->count();
            $nb_vocabulary = $nb_vocabulary - 1;

            if($nb_vocabulary>0)
            {
                $current_id = rand(0, $nb_vocabulary);
                $vocabularies =Db::table("hao_dictionary_vocabularies")
                    ->where('language_id', '=', $language_id)
                    ->where('id', '<>', $vocabulary_id)
                    ->get();
                $vocabulary = $vocabularies[$current_id];

                $current_id = $vocabulary->id;
                $this->vars['name'] = $vocabulary->name;
                $translation = Db::table("hao_dictionary_translations")
                                ->join("hao_dictionary_grammaticalgenders",
                                    "hao_dictionary_translations.grammaticalgender_id",
                                    "=",
                                    "hao_dictionary_grammaticalgenders.id")
                                ->join("hao_dictionary_partofspeeches",
                                    "hao_dictionary_partofspeeches.id",
                                    "=",
                                    "hao_dictionary_translations.partofspeeche_id")
                                ->join("hao_dictionary_singularandplurals",
                                    "hao_dictionary_singularandplurals.id",
                                    "=",
                                    "hao_dictionary_translations.singularandplural_id")
                                ->where("hao_dictionary_translations.vocabulary_id", "=", $current_id)
                                ->orderBy("hao_dictionary_translations.grammaticalgender_id",
                                    "hao_dictionary_translations.partofspeeche_id",
                                    "hao_dictionary_translations.singularandplural_id")
                                ->select("hao_dictionary_translations.name as name",
                                    "hao_dictionary_translations.description as description",
                                    "hao_dictionary_grammaticalgenders.name as grammaticalgender",
                                    "hao_dictionary_partofspeeches.name as partofspeech",
                                    "hao_dictionary_singularandplurals.name as singularandplural")
                                ->get();
                $this->vars['translations'] = $translation;
            }
        }
    }
}
