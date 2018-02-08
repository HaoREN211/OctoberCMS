<?php namespace Elipce\LimeSurvey\Behaviors;

use Elipce\LimeSurvey\Classes\LimeSurveyService;
use October\Rain\Support\Facades\Flash;
use Backend\Classes\ControllerBehavior;
use Illuminate\Support\Facades\Lang;
use Elipce\LimeSurvey\Models\Survey;
use Backend\Classes\Controller;

/**
 * Class TrackingController
 *
 * @package Elipce\LimeSurvey\Behaviors
 */
class TrackingController extends ControllerBehavior
{

    /**
     * Reference to the extended object.
     *
     * @var Controller
     */
    protected $controller;

    /**
     * TrackingController constructor.
     *
     * @param $controller
     */
    public function __construct($controller)
    {
        parent::__construct($controller);
    }

    /**
     * AJAX handler to remind session's participants.
     *
     * @param int $surveyId
     * @return mixed
     */
    public function onRemind($surveyId)
    {
        try {
            /*
             * Fetch survey model
             */
            $survey = Survey::findOrFail($surveyId);
            /*
             * Send remind emails through LimeSurvey API
             */
            $limesurveyService = new LimeSurveyService($survey->session);
            $limesurveyService->remindParticipants($survey);
            $limesurveyService->refreshSurveyTokens($survey);
            /*
             * Update relation manager model
            */
            $this->controller->initRelation($survey);
            Flash::success(Lang::get('elipce.limesurvey::lang.backend.surveys.remind_success'));

        } catch (\Exception $e) {

            Flash::warning(Lang::get('elipce.limesurvey::lang.backend.surveys.remind_error'));
            traceLog($e->getTraceAsString());
        }

        /*
         * Refresh relation manager view list
         */

        return [
            '#' . $this->controller->asExtension('RelationController')
                ->relationGetId('view') => $this->controller->asExtension('RelationController')->relationRenderView()
        ];
    }

    /**
     * AJAX handler to refresh participant statuses.
     *
     * @param int $surveyId
     * @return mixed
     */
    public function onRefreshStatuses($surveyId)
    {
        try {
            /*
             * Fetch survey model
             */
            $survey = Survey::findOrFail($surveyId);
            /*
             * Refresh statuses from LimeSurvey
             */
            $limesurveyService = new LimeSurveyService($survey->session);
            $limesurveyService->refreshSurveyTokens($survey);
            /*
             * Update relation manager model
             */
            $this->controller->initRelation($survey);
            Flash::success(Lang::get('elipce.limesurvey::lang.backend.surveys.refresh_success'));

        } catch (\Exception $e) {

            Flash::warning(Lang::get('elipce.limesurvey::lang.backend.surveys.refresh_error'));
            traceLog($e->getTraceAsString());
        }

        /*
         * Refresh relation manager view list
         */

        return [
            '#' . $this->controller->asExtension('RelationController')
                ->relationGetId('view') => $this->controller->asExtension('RelationController')->relationRenderView()
        ];
    }
}