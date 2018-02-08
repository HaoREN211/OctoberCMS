<?php namespace Elipce\Analytics\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Views Back-end Controller
 */
class Views extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string Configuration file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.datasources.analytics.access_views'];

    /**
     * Views constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'analytics_views');
    }
 }