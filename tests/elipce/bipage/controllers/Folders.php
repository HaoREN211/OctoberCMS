<?php namespace Elipce\BiPage\Controllers;

use Backend\Classes\FilterScope;
use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Folders Back-end Controller
 */
class Folders extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Elipce.Multisite.Behaviors.PortalController',
        'Elipce.BiPage.Behaviors.ReorderRelationController'
    ];

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $reorderRelationConfig = 'config_reorder_relation.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.bipage.access_folders'];

    /**
     * Folders constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'bipage', 'folders');
    }

    /**
     * Extend the query used for populating the list
     * after the default query is processed.
     *
     * @param Builder $query
     */
    public function listExtendQuery($query)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend the query used for populating the filter
     * options before the default query is processed.
     *
     * @param Builder $query
     * @param FilterScope $scope
     */
    public function listFilterExtendQuery($query, $scope)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend the query used for finding the form model. Extra conditions
     * can be applied to the query, for example, $query->withTrashed();
     * @param October\Rain\Database\Builder $query
     * @return void
     */
    public function formExtendQuery($query)
    {
        $query->isAllowed($this->user);
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
        /*
         * Get connected user
         */
        $user = $this->user;
        /*
         * Filter manage view list
         */
        $widget->bindEvent('list.extendQuery', function ($query) use ($user) {
            $query->isAllowed($user);
        });
    }

    /**
     * The view widget is often refreshed when the manage widget makes a change,
     * you can use this method to inject additional containers when this process
     * occurs. Return an array with the extra values to send to the browser, eg:
     *
     * return ['#myCounter' => 'Total records: 6'];
     *
     * @param string $field
     * @return array
     */
    public function relationExtendRefreshResults($field)
    {
        return [
            '#Form-field-Folder-statistics-group' =>
                $this->makePartial('statistics_field', $this->vars, false)
        ];
    }
}