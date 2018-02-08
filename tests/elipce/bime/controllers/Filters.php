<?php namespace Elipce\Bime\Controllers;

use Backend\Classes\FilterScope;
use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Filters Back-end Controller
 */
class Filters extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController'
    ];

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = [
        'elipce.datasources.bime.access_filters',
        'elipce.datasources.bime.access_parameters'
    ];

    /**
     * Filters constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'bime_filters');
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
     * Extend query used for populating
     * the relation form widget options.
     *
     * @param Builder $query
     * @param string $field
     */
    public function formRelationExtendQuery($query, $field)
    {
        if ($field === 'page') {
            $collection = $this->formGetModel()->collection;
            $query->where('collection_id', $collection->id);
        } else if ($field === 'group') {
            $collection = $this->formGetModel()->collection;
            $query->whereIn('id', $collection->bime_groups);
        } else if ($field === 'viewer') {
            $collection = $this->formGetModel()->collection;
            $query->whereIn('group_id', $collection->bime_groups);
        }
    }

    /**
     * Called after the form fields are defined.
     * @param WidgetBase $widget
     */
    public function formExtendFields($widget)
    {
        /*
         * Only update context
         */
        if ($this->formGetContext() !== 'update') return;
        /*
         * Get parameter type
         */
        $parameterType = $widget->model->parameter->type;
        /*
         * Dynamic value field according to parameter type
         */
        if ($parameterType === 'integer') {
            $widget->addFields([
                'value' => [
                    'label' => 'elipce.bime::lang.backend.filters.value_label',
                    'commentAbove' => 'elipce.bime::lang.backend.filters.value_comment',
                    'required' => 'true',
                    'type' => 'number',
                    'span' => 'right'
                ]
            ]);
        } else if ($parameterType === 'date') {
            $widget->addFields([
                'value' => [
                    'label' => 'elipce.bime::lang.backend.filters.value_label',
                    'commentAbove' => 'elipce.bime::lang.backend.filters.value_comment',
                    'type' => 'datepicker',
                    'required' => 'true',
                    'mode' => 'date',
                    'span' => 'right'
                ]
            ]);
        }
    }

    /**
     * Extend supplied model used by create and update actions, the model can
     * be altered by overriding it in the controller.
     *
     * @param Model $model
     */
    public function formExtendModel($model)
    {
        /*
         * Only update context
         */
        if ($this->formGetContext() !== 'update') return;
        /*
         * Dynamic validation rules according to parameter type
         */
        if ($model->parameter->type === 'date') {
            $model->rules['value'] = 'required:update|date';
        } else if ($model->parameter->type === 'integer') {
            $model->rules['value'] = 'required:update|integer';
        }
    }
}