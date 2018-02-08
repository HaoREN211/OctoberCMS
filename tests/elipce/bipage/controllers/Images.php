<?php namespace Elipce\BiPage\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Images Back-end Controller
 */
class Images extends Controller
{

    /**
     * @var array Controller behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    // For side panel
    public $bodyClass = 'compact-container';

    // Config files
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * Images constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'images');
    }

    /**
     * Overriding backend list query
     *
     * @param $query
     */
    public function listExtendQuery($query)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend query used for populating
     * the relation form widget options
     *
     * @param $query
     * @param $field
     */
    public function formRelationExtendQuery($query, $field)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend the query used for populating the filter
     * options before the default query is processed.
     *
     * @param $query
     * @param $scope
     */
    public function listFilterExtendQuery($query, $scope)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend the query used for finding the form model. Extra conditions
     * can be applied to the query, for example, $query->withTrashed();
     *
     * @param October\Rain\Database\Builder $query
     *
     * @return void
     */
    public function formExtendQuery($query)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Called after the form is saved.
     *
     * @param $model
     */
    public function formAfterSave($model)
    {
        $model->collection->dashboards()->attach($model->id);
    }

}