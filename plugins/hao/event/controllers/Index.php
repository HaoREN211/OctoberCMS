<?php namespace Hao\Event\Controllers;

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

        BackendMenu::setContext('Hao.Event', 'event', 'index');
    }


    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('hao.event::lang.plugin.name');
    }
}
