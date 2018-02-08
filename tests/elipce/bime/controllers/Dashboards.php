<?php namespace Elipce\Bime\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;

/**
 * Dashboards Back-end Controller
 */
class Dashboards extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.RelationController',
        'Elipce.BiPage.Behaviors.ReorderRelationController'
    ];

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $reorderRelationConfig = 'config_reorder_relation.yaml';

    /**
     * @var array List configuration files
     */
    public $listConfig = [
        'default' => 'config_list.yaml',
        'admin' => 'config_list_admin.yaml'
    ];

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.datasources.bime.access_dashboards'];

    /**
     * Dashboards constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'bime_dashboards');
    }

    /**
     * Overriding index controller action.
     */
    public function index()
    {
        /*
         * Load list config according to backend user privileges
         */
        $this->vars['definition'] = $this->user->isSuperUser() ? 'admin' : 'default';
        $this->asExtension('ListController')->index();
    }

    /**
     * CExtend the query used for populating the list
     * after the default query is processed.
     *
     * @param Builder $query
     */
    public function listExtendQuery($query)
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
}