<?php namespace Elipce\LimeSurvey\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Lang;

/**
 * Index Backend Controller
 */
class Index extends Controller
{

    /**
     * Index constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.LimeSurvey', 'limesurvey', 'index');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('elipce.limesurvey::lang.plugin.name');
    }
}