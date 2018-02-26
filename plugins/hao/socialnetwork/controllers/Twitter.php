<?php namespace Hao\Socialnetwork\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Lang;

/**
 * Twitter Back-end Controller
 */
class Twitter extends Controller
{
    /**
     * Twitter constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Socialnetwork', 'socialnetwork', 'twitter');
    }

    /**
     * Display index.
     */
    public function index()
    {
        $this->pageTitle = Lang::get('hao.socialnetwork::lang.twitter.name');
    }
}
