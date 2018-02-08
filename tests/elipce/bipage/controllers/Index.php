<?php namespace Elipce\BiPage\Controllers;

use Illuminate\Support\Facades\Lang;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

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
        BackendMenu::setContext('Elipce.BiPage', 'bipage', 'index');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('elipce.bipage::lang.plugin.name');
    }
}