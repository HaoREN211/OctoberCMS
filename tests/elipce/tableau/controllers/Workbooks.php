<?php namespace Elipce\Tableau\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Workbooks Back-end Controller
 */
class Workbooks extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string List configuration file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.datasources.tableau.access_workbooks'];

    /**
     * Workbooks constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'tableau_workbooks');
    }
}