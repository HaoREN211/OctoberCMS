<?php namespace Elipce\LimeSurvey\Controllers;

use Elipce\LimeSurvey\Classes\LimeSurveyServiceException;
use Elipce\LimeSurvey\Classes\LimeSurveyClientException;
use Elipce\LimeSurvey\Models\Story;
use October\Rain\Exception\ApplicationException;
use Elipce\LimeSurvey\Classes\LimeSurveyService;
use October\Rain\Exception\ValidationException;
use Elipce\LimeSurvey\Classes\ImportService;
use Elipce\LimeSurvey\Models\Session;
use Illuminate\Support\Facades\Lang;
use Elipce\Multisite\Models\Portal;
use October\Rain\Database\Builder;
use Backend\Classes\FilterScope;
use October\Rain\Database\Model;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

/**
 * Sessions Back-end Controller
 */
class Sessions extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Elipce.Multisite.Behaviors.PortalController'
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
    public $requiredPermissions = ['elipce.limesurvey.access_sessions'];

    /**
     * Sessions constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.LimeSurvey', 'limesurvey', 'sessions');
    }

    /**
     * Overriding create controller action.
     */
    public function create($context = null)
    {
        $this->asExtension('FormController')->create($context);

        /*
         * Get model and form session key
         */
        $sessionKey = $this->formGetWidget()->getSessionKey();
        $data = $this->formGetWidget()->getSaveData();
        $model = $this->formGetModel();
        /*
         * Fetch story model
         */
        $storyId = array_key_exists('story', $data) ? $data['story'] : 0;
        $story = Story::find($storyId);
        /*
         * Get deferred Excel file
         */
        $file = $model->import_file()->withDeferred($sessionKey)->first();
        /*
         * Load participant columns
         */
        $columns = ImportService::loadParticipantColumns($file);
        /*
         * Add dynamic method to populate dropdown fields
         */
        $model->addDynamicMethod('getDropdownOptions',
            function($fieldName = null) use ($data, $story, $columns) {
                /*
                 * Custom columns
                 */
                if ($fieldName == 'column') {
                    /*
                     * Used values for required columns
                     */
                    $usedValues = [
                        $data['uid_column'] ?: null,
                        $data['email_column'] ?: null,
                        $data['sn_column'] ?: null,
                        $data['fn_column'] ?: null,
                        $data['role_column'] ?: null
                    ];
                    /*
                     * Exclude story mask columns
                     */
                    if ($story) {
                        $mask = $story->columns->lists('name');
                        $usedValues = array_merge($usedValues, $mask);
                    }
                    /*
                     * Merge all possible values for required columns
                     */
                    $requiredValues = array_merge(Session::DEFAULT_COLUMNS, $usedValues);
                    /*
                     * Remove this values from available columns
                     */
                    $columns = array_diff($columns, $requiredValues);

                } else {
                    /*
                     * Handle story mask required columns
                     */
                    if ($story) {

                        $requiredColumns = $story->required_columns->lists('name', 'field');

                        if (array_key_exists($fieldName, $requiredColumns)) {

                            if (in_array($requiredColumns[$fieldName], $columns)) {

                                $columns = [$requiredColumns[$fieldName]];
                            }
                        }
                    }
                }

                return array_combine($columns, $columns);
            }
        );
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
     * Extend supplied model used by create and update actions, the model can
     * be altered by overriding it in the controller.
     *
     * @param Model $model
     * @return Model
     */
    public function formExtendModel($model)
    {
        /*
         * Get connected user
         */
        $user = $this->user;
        /*
         * Add author
         */
        $model->created_by = $this->user->id;
        /*
         * Add dynamic method for portal dropdown
         */
        $model->addDynamicMethod('getPortalOptions', function() use ($model, $user) {

            return Portal::isAllowed($user)
                ->whereNotNull('limesurvey_account_id')
                ->lists('name', 'id');
        });
        /*
         * Add dynamic method for story dropdown
         */
        $model->addDynamicMethod('getStoryOptions', function() use($model, $user) {
            return Story::isAllowed($user)->ready()->lists('name', 'id');
        });

        return $model;
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
     * Called before the create form model is saved.
     *
     * @param Session $model
     * @throws ValidationException
     */
    public function formBeforeCreate($model)
    {
        $data = $this->formGetWidget()->getSaveData();
        $sessionKey = $this->formGetWidget()->getSessionKey();
        $columns = array_column($data['custom_columns'], 'column');
        /*
         * Prevent form saving empty custom attribute column
         */
        if (in_array(null, $columns)) {
            /*
             * Cancel deferred file
             */
            $model->cancelDeferred($sessionKey);
            $this->initForm($model);

            throw new ValidationException([
                Lang::get('elipce.limesurvey::lang.backend.sessions.invalid_column')
            ]);
        }
    }

    /**
     * Called before the form model is saved.
     *
     * @param Session $model
     * @throws ValidationException
     */
    public function formBeforeSave($model)
    {
        $data = $this->formGetWidget()->getSaveData();
        $sessionKey = $this->formGetWidget()->getSessionKey();
        /*
         * Participant importation
         */
        try {
            $importService = new ImportService($model, $data, $sessionKey);
            $participantsToSave = $importService->getParticipantsToSave();
            $columnsToSave = $importService->getColumnsToSave();
        } catch (ValidationException $e) {
            $model->cancelDeferred($sessionKey);
            $this->initForm($model);
            throw $e;
        }
        /*
         * Deferred bindings
         */
        $model->bindEvent('model.afterSave', function() use ($model, $participantsToSave, $columnsToSave) {
            $model->participants()->saveMany($participantsToSave);
            $model->session_columns()->saveMany($columnsToSave);
        });
    }

    /**
     * Called after the update form is saved.
     *
     * @param Session $model
     * @throws ApplicationException
     */
    public function formAfterUpdate($model)
    {
        /*
         * Invite participants
         */
        try {
            $limesurveyService = new LimeSurveyService($model);
            /*
             * Recalculate pending and incoming surveys
             */
            foreach ($model->inactive_surveys as $survey) {
                $survey->calculateDates();
                $limesurveyService->updateSurvey($survey);
                $survey->save();
            }

            $limesurveyService->inviteParticipants();

        } catch (LimeSurveyServiceException $e) {
            throw new ApplicationException($e->getMessage());

        } catch (LimeSurveyClientException $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    /**
     * Called after the form model is deleted.
     *
     * @param Model
     */
    public function formAfterDelete($model)
    {
        $limesurveyService = new LimeSurveyService($model);
        $limesurveyService->clean();
    }

    /**
     * Called after the creation form is saved.
     *
     * @param Session $model
     * @throws ApplicationException|ValidationException
     */
    public function formAfterCreate($model)
    {
        /*
         * Create surveys and invite participants
         */
        try {
            $limesurveyService = new LimeSurveyService($model);
            $limesurveyService->instantiateSurveys();
            $limesurveyService->inviteParticipants();

        } catch (LimeSurveyServiceException $e) {
            $this->initForm($model);
            Session::find($model->id)->delete();
            throw new ApplicationException($e->getMessage());

        } catch (LimeSurveyClientException $e) {
            $this->initForm($model);
            Session::find($model->id)->delete();
            throw new ValidationException([
                Lang::get('elipce.limesurvey::lang.backend.sessions.api_error')
            ]);
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
            '#Form-field-Session-statistics-group' =>
                $this->makePartial('statistics_field', $this->vars, false)
        ];
    }
}