<?php namespace Elipce\Bime\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Groups Back-end Controller
 */
class Groups extends Controller
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
    public $requiredPermissions = ['elipce.datasources.bime.access_groups'];

    /**
     * Groups constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'bime_groups');
    }
}