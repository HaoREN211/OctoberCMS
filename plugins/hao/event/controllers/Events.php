<?php namespace Hao\Event\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Events Back-end Controller
 */
class Events extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hao.Event', 'event', 'events');
    }

    public function relationExtendManageWidget($widget, $field, $model)
    {
    }
}
