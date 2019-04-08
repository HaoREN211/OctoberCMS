<?php namespace Hao\Journal\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Journal Back-end Controller
 */
class Journal extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Journal', 'journal', 'journal');
    }
}
