<?php namespace Elipce\BiPage\Controllers;

use Illuminate\Support\Facades\Lang;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Datasources Backend Controller
 */
class Datasources extends Controller
{
    /**
     * Datasources constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'index');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('elipce.bipage::lang.plugin.menus.datasources');
    }
}