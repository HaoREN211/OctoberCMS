<?php namespace Elipce\Analytics\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Charts Back-end Controller
 */
class Charts extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.datasources.analytics.access_charts'];

    /**
     * Charts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'analytics_charts');
    }
}