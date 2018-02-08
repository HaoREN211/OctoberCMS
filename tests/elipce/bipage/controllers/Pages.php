<?php namespace Elipce\BiPage\Controllers;

use Backend\Classes\FilterScope;
use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Pages Back-end Controller
 */
class Pages extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
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
    public $requiredPermissions = [
        'elipce.bipage.access_pages',
        'elipce.bipage.access_visualizations'
    ];

    /**
     * Pages constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'bipage', 'pages');
        /*
         * Prevent unauthorized backend user
         * to access create form
         */
        $this->bindEvent('page.beforeDisplay', function($action, $params) {
            if ($action === 'create') {
                array_pop($this->requiredPermissions);
            }
        });
    }

    /**
     * Overriding update controller action.
     */
    public function update($recordId, $context = null)
    {
        /*
         * Call the SortingRelationController behavior update() method
         */
        $this->asExtension('FormController')->update($recordId, $context);
        /*
         * Enable preview mode for restricted backend users
         */
        $this->formGetWidget()->previewMode = ! $this->user->hasAccess('elipce.bipage.access_pages');
    }

    /**
     * Extend the query used for populating the list
     * after the default query is processed.
     *
     * @param Builder $query
     */
    public function listExtendQuery($query)
    {
        $query->isAllowed($this->user, false);
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
     *
     * @param October\Rain\Database\Builder $query
     *
     * @return void
     */
    public function formExtendQuery($query)
    {
        $query->isAllowed($this->user, false);
    }

    /**
     * Extend supplied model used by create and update actions, the model can
     * be altered by overriding it in the controller.
     *
     * @param Model $model
     *
     * @return Model
     */
    public function formExtendModel($model)
    {
        /*
         * No dashboards => page unpublished
         */
        if ($model->dashboards->count() === 0) {
            $model->published = false;
        }

        return $model;
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
     * Provides an opportunity to manipulate the manage widget.
     *
     * @param WidgetBase $widget
     * @param string $field
     * @param Model $model
     */
    public function relationExtendManageWidget($widget, $field, $model)
    {
        $widget->bindEvent('list.extendQuery', function($query) use ($model) {
            $query->applyCollection($model->collection->id);
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
     *
     * @return array
     */
    public function relationExtendRefreshResults($field)
    {
        $model = $this->formGetModel();

        /*
         * No dashboards => page unpublished
         */
        if ($model->dashboards->count() === 0) {
            $model->published = false;
            $model->save();
            /*
             * Update form model
             */
            $this->initForm($model, 'update');

            /*
             * Update form secondary tab
             */

            return [
                $this->formGetId() . ' #Form-secondaryContainer' =>
                    $this->formRender(['section' => 'secondary'])
            ];
        }

        return [];
    }
}