<?php namespace Elipce\LimeSurvey\Controllers;

use Elipce\LimeSurvey\Classes\LimeSurveyService;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Model;

/**
 * Surveys Back-end Controller
 */
class Surveys extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.RelationController',
        'Elipce.LimeSurvey.Behaviors.TrackingController'
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

    /**
     * @var array Required files
     */
    public $requiredPermissions = ['elipce.limesurvey.access_sessions'];

    /**
     * Surveys constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.LimeSurvey', 'limesurvey', 'sessions');
    }

    /**
     * Called after update form is saved.
     *
     * @param Model $model
     */
    public function formAfterUpdate($model)
    {
        /*
         * Update survey on LimeSurvey
         */
        $limesurveyService = new LimeSurveyService($model->session);
        $limesurveyService->updateSurvey($model);
    }
}