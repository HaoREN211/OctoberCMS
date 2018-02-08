<?php namespace Elipce\BiPage\Controllers;

use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Model;

/**
 * Focus Back-end Controller
 */
class Focus extends Controller
{
    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Elipce.Multisite.Behaviors.RedirectionController'
    ];

    /**
     * @var string Configuration files
     */
    public $relationConfig = 'config_relation.yaml';
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.multisite.access_focus'];

    /**
     * Focus constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.Multisite', 'multisite', 'focus');
    }

    /**
     * Provides an opportunity to manipulate the manage widget.
     *
     * @param WidgetBase $widget
     * @param string $field
     * @param Model $model
     */
    public function relationExtendManageWidget($widget, $field, $model)
    {
        // Filter manage view list
        $widget->bindEvent('list.extendQuery', function ($query) use ($model) {
            $query->applyPortal($model->id)->published();
        });
    }
}