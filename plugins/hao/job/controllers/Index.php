<?php namespace Hao\Job\Controllers;

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

        BackendMenu::setContext('Hao.Job', 'job', 'index');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('hao.job::lang.plugin.name');
    }
}
