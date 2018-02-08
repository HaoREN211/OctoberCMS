<?php namespace Elipce\LimeSurvey\Controllers;

use Elipce\LimeSurvey\Models\Column;
use Illuminate\Database\Eloquent\ScopeInterface;
use Elipce\BiPage\Models\Collection;
use Elipce\LimeSurvey\Models\Session;
use Illuminate\Support\Facades\Lang;
use October\Rain\Database\Builder;
use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Model;

/**
 * Stories Back-end Controller
 */
class Stories extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
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
    public $relationConfig = 'config_relation.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.limesurvey.access_stories'];

    /**
     * Stories constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.LimeSurvey', 'limesurvey', 'stories');
    }

    /**
     * Overriding update controller action.
     */
    public function update($recordId, $context = null)
    {
        /*
         * Relation managers read only if story is used by sessions
         */
        $this->vars['used'] = Session::where('story_id', $recordId)->count() > 0;

        /*
         * Call the FormController behavior update() method
         */

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    /**
     * Extend the query used for populating the filter
     * options before the default query is processed.
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
     * @param ScopeInterface $scope
     */
    public function listFilterExtendQuery($query, $scope)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend supplied model used by create and update actions, the model can
     * be altered by overriding it in the controller.
     *
     * @param Model $model
     * @return Model
     */
    public function formExtendModel($model)
    {
        $user = $this->user;

        /*
         * Dynamic method for collection checkbox list
         */
        $model->addDynamicMethod('getCollectionOptions', function() use ($user) {
            return Collection::isAllowed($user)->lists('name', 'id');
        });

        return $model;
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
         * Only pre-surveys relation
         */
        if ($field == 'presurveys') {
            /*
             * Dynamic method for roles checkbox list
             */
            $widget->model->addDynamicMethod('getRolesOptions', function() use ($model) {
                return $model->roles->lists('name', 'id');
            });
        }
        /*
         * Only columns relation
         */
        if ($field == 'columns') {

            /*
             * Add dynamic method to provide field options
             */
            $widget->model->addDynamicMethod('getFieldOptions', function() use ($model) {
                /*
                 * Remove used required fields
                 */
                $fields = array_diff_key(
                    Column::REQUIRED_FIELDS,
                    $model->columns->lists('field', 'field')
                );

                $options = [];

                /*
                 * Translate required attribute names
                 */
                foreach ($fields as $field => $label) {
                    $options[$field] = Lang::get($label);
                }

                return $options;
            });
        }
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
            '#Form-field-Story-statistics-group' =>
                $this->makePartial('statistics_field', $this->vars, false)
        ];
    }
}