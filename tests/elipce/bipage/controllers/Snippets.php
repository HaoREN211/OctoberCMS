<?php namespace Elipce\BiPage\Controllers;

use Backend\Classes\FilterScope;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Elipce\BiPage\Models\Snippet;
use October\Rain\Database\Builder;

/**
 * Snippets Back-end Controller
 */
class Snippets extends Controller
{

    /**
     * @var array Controller behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * Snippets constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'snippets');
    }

    /**
     * Overriding preview controller action.
     *
     * @param int $recordId
     * @param null $context
     * @return mixed
     */
    public function preview($recordId, $context = null)
    {
        /*
         * Remove sidebar menu
         */
        $this->bodyClass = '';
        /*
         * Call the FormController behavior preview() method
         */
        return $this->asExtension('FormController')->preview($recordId, $context);
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
     * Extend query used for populating
     * the relation form widget options.
     *
     * @param Builder $query
     * @param string $field
     */
    public function formRelationExtendQuery($query, $field)
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
     * Called after the form is saved.
     * @param Snippet $model
     */
    public function formAfterSave($model)
    {
        $model->collection->dashboards()->attach($model->id);
    }
}