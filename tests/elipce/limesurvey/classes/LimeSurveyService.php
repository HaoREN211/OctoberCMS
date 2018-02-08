<?php namespace Elipce\LimeSurvey\Classes;

use Elipce\LimeSurvey\Models\Account;
use Elipce\LimeSurvey\Models\Participant;
use Elipce\LimeSurvey\Models\PreSurvey;
use Elipce\LimeSurvey\Models\Role;
use Elipce\LimeSurvey\Models\Session;
use Elipce\LimeSurvey\Models\Survey;
use Illuminate\Support\Facades\File;
use October\Rain\Database\Model;
use October\Rain\Support\Collection;

/**
 * Class LimeSurveyServiceException
 *
 * @package Elipce\LimeSurvey\Classes
 */
class LimeSurveyServiceException extends \Exception
{

}

/**
 * Class LimeSurveyService
 *
 * @package Elipce\LimeSurvey\Classes
 */
class LimeSurveyService
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var LimeSurveyClient
     */
    protected $apiClient = null;

    /**
     * SessionService constructor.
     *
     * @param Session|Model $session
     */
    public function __construct($session)
    {
        $this->session = $session;
        $this->account = $this->session->portal->limesurvey_account;

    }

    /**
     * API Client getter.
     *
     * @return LimeSurveyClient|null
     */
    protected function getApiClient()
    {
        if (empty($this->apiClient)) {
            $this->apiClient = new LimeSurveyClient($this->account->url);
            $this->apiClient->login($this->account->login, $this->account->password);
        }

        return $this->apiClient;
    }

    /**
     * Instantiate session's surveys.
     *
     * @throws LimeSurveyServiceException
     */
    public function instantiateSurveys()
    {
        /*
         * Create surveys
         */
        foreach ($this->session->story->presurveys as $presurvey) {
            foreach ($presurvey->roles as $role) {
                /*
                 * Fetch participants of a given role on a given session
                 */
                $participants = Participant::where('session_id', $this->session->id)
                    ->where('role_id', $role->id)
                    ->lists('id');
                /*
                 * No participant => no survey creation
                 */
                if (count($participants) > 0) {
                    $survey = $this->createSurveys($presurvey, $role);
                    $survey->participants()->attach($participants);
                }
            }
        }
        /*
         * Reload surveys and link them together
         */
        $surveys = $this->session->surveys()->get();
        $this->linkSurveys($surveys);
    }

    /**
     * Create links between surveys.
     *
     * @param Collection $surveys
     * @throws LimeSurveyServiceException
     */
    protected function linkSurveys($surveys)
    {
        /*
         * Add links between surveys
         */
        foreach ($surveys as $survey) {
            /*
             * Retrieve all current survey questions
             */
            $questions = $this->getApiClient()->list_questions($survey->external_id);
            $questions = Collection::make($questions)->keyBy('title');

            foreach ($survey->template->linked_questions as $question) {
                /*
                 * Build question code id
                 */
                if ($q = $questions->get($question->name)) {
                    $code = $q['sid'] . 'X' . $q['gid'] . 'X' . $q['qid'];
                } else {
                    throw new LimeSurveyServiceException('Question not found !');
                }
                /*
                 * Find linked survey
                 */
                $query = $surveys
                    ->where('template_id', $question->answer->template_id)
                    ->where('role_id', $survey->role_id);

                if ($link = $query->first()) {
                    $survey->links()->attach($link->id, [
                        'question' => $code,
                        'answer'   => $question->answer->name
                    ]);
                }
            }
        }
    }

    /**
     * Create surveys for a given role.
     *
     * @throws LimeSurveyServiceException
     * @param PreSurvey $preSurvey
     * @param Role $role
     *
     * @return Survey
     */
    protected function createSurveys(PreSurvey $preSurvey, Role $role)
    {
        /*
         * Create new survey
         */
        $survey = new Survey();
        $survey->fill([
            'name'       => $preSurvey->name,
            'start_days' => $preSurvey->start_days,
            'end_days'   => $preSurvey->end_days,
            'duration'   => $preSurvey->duration
        ]);
        $survey->role()->associate($role);
        $survey->session()->associate($this->session);
        $survey->template()->associate($preSurvey->template);
        /*
         * Calculate survey dates
         */
        $survey->calculateDates();
        /*
         * Get LimeSurvey API client
         */
        $apiClient = $this->getApiClient();
        /*
         * Encode template data in BASE 64
         */
        $templatePath = $preSurvey->template->structure->getLocalPath();
        $templateBase64 = base64_encode(File::get($templatePath));
        /*
         * Create survey on LimeSurvey
         */
        $surveyName = utf8_decode("{$survey->slug} ({$role->slug})");
        $response = $apiClient->import_survey($templateBase64, 'lss', $surveyName, 0);
        /*
         * Check response
         */
        if (is_int($response)) {
            $survey->external_id = $response;
        } else {
            throw new LimeSurveyServiceException("Survey creation error !");
        }
        /*
         * Set survey's dates
         */
        $apiClient->set_survey_properties($survey->external_id, [
            'startdate'                => $survey->start_date->toDateTimeString(),
            'expires'                  => $survey->end_date->toDateTimeString(),
            'alloweditaftercompletion' => 'Y', // TODO: delete this line !
            'tokenanswerspersistence'  => 'Y', // TODO: delete this line !
            'language'                 => 'fr'
        ]);
        /*
         * Save the survey model in database
         */
        $survey->save();

        return $survey;
    }

    /**
     * Send remind emails for a given survey to participants.
     *
     * @param Survey|null $survey
     */
    public function remindParticipants($survey = null)
    {
        $apiClient = $this->getApiClient();

        if ($survey) {
            /*
             * Send remind emails to given survey's participants
             */
            $apiClient->remind_participants($survey->external_id);

        } else {
            /*
             * Fetch pending surveys
             */
            $surveys = $this->session->surveys()->active()->get();

            foreach ($surveys as $survey) {
                /*
                 * Send remind emails to participants
                 */
                $apiClient->remind_participants($survey->external_id);
            }
        }
    }

    /**
     * Create participants on LimeSurvey and send invitations for pending surveys.
     */
    public function inviteParticipants()
    {
        $apiClient = $this->getApiClient();
        /*
         * Fetch incoming surveys
         */
        $surveys = $this->session->incoming_surveys()->get();

        foreach ($surveys as $survey) {
            /*
             * Activate survey and his tokens table
             */
            $apiClient->activate_survey($survey->external_id);
            /*
             * Add custom field descriptions to the survey + fill dates
             */
            $fDescriptions = $this->generateFieldDescriptions($survey);
            $apiClient->set_survey_properties($survey->external_id, [
                'attributedescriptions' => json_encode($fDescriptions)
            ]);
            /*
             * Activate tokens and set up custom fields (integers)
             */
            $count = range(1, count($fDescriptions));
            $apiClient->activate_tokens($survey->external_id, $count);
            /*
             * Add participants to the survey
             */
            $participantsData = $this->getParticipantsData($survey);
            $response = $apiClient->add_participants($survey->external_id, $participantsData);
            /*
             * Associate surveys and participants with their token
             */
            $tokens = [];
            foreach ($response as $index => $p) {
                $tokens[(int) $index] = ['token' => $p['token']];
            }
            $survey->participants()->sync($tokens);
            /*
             * Send emails
             */
            $apiClient->invite_participants($survey->external_id);
            /*
             * Copy linked answers
             */
            $this->duplicateAnswers($survey);
            /*
             * Activate local survey model
             */
            $survey->active = true;
            $survey->save();
        }
    }

    /**
     * Copy all active linked surveys' answers to linked questions.
     */
    public function duplicateAllAnswers()
    {
        $surveys = $this->session->active_surveys()->get();

        foreach ($surveys as $survey) {
            $this->duplicateAnswers($survey);
        }
    }

    /**
     * Copy linked survey's answers to linked questions.
     *
     * @param Survey $survey
     */
    public function duplicateAnswers(Survey $survey)
    {
        $apiClient = $this->getApiClient();
        $participants = $survey->participants;
        /*
         * Handle linked questions/responses
         */
        foreach ($survey->links as $link) {
            foreach ($link->participants as $linkedParticipant) {
                /*
                 * Get survey new participant
                 */
                $participant = $participants
                    ->where('id', $linkedParticipant->id)
                    ->first();
                /*
                 * Pass orphan participants
                 */
                if (empty($participant)) {
                    continue;
                }
                /*
                 * Fetch participant answers
                 */
                $answers = $this->getAnswers($link, $linkedParticipant->pivot->token);
                $emptyAnswers = $this->getAnswers($survey, $participant->pivot->token);
                /*
                 * Copy linked answer to linked question
                 */
                if ($answers) {
                    $apiClient->update_response($survey->external_id, (object) [
                        'token'                => $participant->pivot->token,
                        'id'                   => $emptyAnswers['id'],
                        $link->pivot->question => $answers[$link->pivot->answer]
                    ]);
                }
            }
        }
    }

    /**
     * Returns answers for a given survey and participant.
     *
     * @param $survey
     * @param $token
     * @return mixed|null
     */
    protected function getAnswers($survey, $token)
    {
        $apiClient = $this->getApiClient();

        $data = $apiClient->export_responses_by_token(
            $survey->external_id, 'json',
            $token
        );

        // Empty data
        if (! is_string($data)) {
            return null;
        }

        $json = base64_decode($data);
        $response = json_decode($json, true);

        if (array_key_exists('responses', $response)) {
            return array_pop($response['responses'][0]);
        } else {
            return null;
        }
    }

    /**
     * Delete archived session's surveys from LimeSurvey.
     */
    public function clean()
    {
        $surveys = $this->session->surveys()->deletable()->get();

        foreach($surveys as $survey) {
            $this->getApiClient()
                ->delete_survey($survey->external_id);
        }
    }

    /**
     * Update a survey start date and expiration date on LimeSurvey
     *
     * @param Survey $survey
     */
    public function updateSurvey(Survey $survey)
    {
        $this->getApiClient()->set_survey_properties($survey->external_id, [
            'startdate' => $survey->start_date->toDateTimeString(),
            'expires'   => $survey->end_date->toDateTimeString()
        ]);
    }

    /**
     * Update given participant attributes on LimeSurvey.
     *
     * @param Participant $participant
     */
    public function updateParticipant(Participant $participant)
    {
        foreach ($participant->active_surveys as $survey) {
            $this->getApiClient()
                ->set_participant_properties(
                    $survey->external_id,
                    ['token' => $survey->pivot->token],
                    $this->getParticipantData($survey, $participant)
                );
        }
    }

    /**
     * Refresh a given survey's tokens with LimeSurvey data.
     *
     * @param Survey $survey
     */
    public function refreshSurveyTokens(Survey $survey)
    {
        foreach ($survey->participants as $participant) {

            if ($participant->pivot->token) {

                $response = $this->getApiClient()
                    ->get_participant_properties(
                        $survey->external_id,
                        ['token' => $participant->pivot->token],
                        ['completed', 'remindersent']
                    );

                $participant->pivot->reminder_sent = $response['remindersent'] != 'N';
                $participant->pivot->completed = $response['completed'] != 'N';
                $participant->pivot->save();
            }
        }
    }

    /**
     * Generate extra field descriptions.
     *
     * @param Survey $survey
     * @return array
     */
    protected function generateFieldDescriptions(Survey $survey)
    {
        $fields = [
            'attribute_1' => ['description' => 'session_id'],
            'attribute_2' => ['description' => 'role'],
            'attribute_3' => ['description' => 'uid']
        ];

        $survey->participants->first()->custom_attributes
            ->each(function($a) use (&$fields) {
                $i = count($fields) + 1;
                $fields['attribute_' . $i] = [
                    'description' => $a->name
                ];
            });

        return $fields;
    }

    /**
     * Participants data getter.
     *
     * @param Survey $survey
     * @return object
     */
    protected function getParticipantsData(Survey $survey)
    {
        $participantsData = [];

        foreach ($survey->participants as $participant) {
            $participantsData[$participant->id . $participant->sn] =
                $this->getParticipantData($survey, $participant);
        }

        return (object) $participantsData;
    }

    /**
     * Participant data getter.
     *
     * @param Survey $survey
     * @param Participant $participant
     * @return array
     */
    protected function getParticipantData(Survey $survey, Participant $participant)
    {
        $fields = [
            'firstname'  => utf8_decode($participant->fn),
            'lastname'   => utf8_decode($participant->sn),
            'email'      => $participant->email,
            'validfrom'  => $survey->start_date->toDateTimeString(),
            'validuntil' => $survey->end_date->toDateTimeString()
        ];

        $extraFields = [
            'attribute_1' => $this->session->id,
            'attribute_2' => utf8_decode($participant->role->name),
            'attribute_3' => $participant->uid
        ];

        foreach ($participant->custom_attributes as $a) {
            $i = count($extraFields) + 1;
            $extraFields['attribute_' . $i] = $a->value;
        }

        return array_merge($fields, $extraFields);
    }
}