<?php namespace Hao\Photo\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Lang;

/**
 * Index Back-end Controller
 */
class Index extends Controller
{
    /**
     * Index constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Photo', 'photo', 'index');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('hao.photo::lang.plugin.name');
    }
}
