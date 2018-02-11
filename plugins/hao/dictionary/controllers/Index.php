<?php namespace Hao\Dictionary\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Lang;

/**
 * Index Back-end Controller
 */
class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Dictionary', 'dictionary', 'index');
    }


    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('hao.dictionary::lang.plugin.name');
    }
}
