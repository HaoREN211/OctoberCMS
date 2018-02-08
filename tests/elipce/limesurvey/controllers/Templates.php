<?php namespace Elipce\LimeSurvey\Controllers;

use Backend\Classes\FilterScope;
use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Elipce\BiPage\Models\Collection;
use Elipce\LimeSurvey\Models\Question;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Templates Back-end Controller
 */
class Templates extends Controller
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
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.limesurvey.access_templates'];

    /**
     * Templates constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.LimeSurvey', 'limesurvey', 'templates');
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
         * Dynamic method for collection dropdown options
         */
        $model->addDynamicMethod('getCollectionOptions', function() use ($model, $user) {
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
         * Dynamic method for answer dropdown options
         */
        $widget->model->addDynamicMethod('getAnswerOptions', function() use ($model) {
            $questions = Question::isAllowed($model)->with('template')->get();
            $options = $questions->lists('fullname', 'id');
            $options[0] = '<>';
            return $options;
        });
    }

    /**
     * After creation form is saved.
     *
     * @param Model $model
     */
    public function formAfterCreate($model)
    {
        $questionsToSave = [];

        /*
         * Read XML LimeSurvey structure data
         */
        $path = $model->structure->getLocalPath();
        $xml = simplexml_load_file($path, 'SimpleXMLElement', LIBXML_NOCDATA);
        $questions = $xml->xpath('questions/rows/row');
        /*
         * Create questions from XML
         */
        foreach ($questions as $question) {
            $name = (string) $question->title;
            $questionsToSave[] = new Question(['name' => $name]);
        }

        $model->questions()->saveMany($questionsToSave);
    }
}