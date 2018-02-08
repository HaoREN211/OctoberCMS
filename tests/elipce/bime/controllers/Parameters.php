<?php namespace Elipce\Bime\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Parameters Back-end Controller
 */
class Parameters extends Controller
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
    public $requiredPermissions = ['elipce.datasources.bime.access_parameters'];

    /**
     * Parameters constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'bime_filters');
    }
}