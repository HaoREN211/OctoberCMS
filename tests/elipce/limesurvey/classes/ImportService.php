<?php namespace Elipce\LimeSurvey\Classes;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use October\Rain\Exception\ValidationException;
use Elipce\LimeSurvey\Models\Session;
use Elipce\LimeSurvey\Models\Participant;
use Elipce\LimeSurvey\Models\Attribute;
use Elipce\LimeSurvey\Models\Story;
use Elipce\LimeSurvey\Models\Column;
use Illuminate\Support\Facades\Lang;
use Maatwebsite\Excel\Facades\Excel;
use October\Rain\Support\Collection;
use System\Models\File;

/**
 * Class ImportService
 *
 * @package Elipce\LimeSurvey\Classes
 */
class ImportService
{

    /**
     * Mask columns
     *
     * @var Collection
     */
    protected $sessionColumns;
    protected $allColumns;

    /**
     * Excel rows
     *
     * @var array
     */
    protected $rows;

    /**
     * Session model
     *
     * @var Session
     */
    protected $session;

    /**
     * ImportService constructor.
     *
     * @param Session $session
     * @param array $data
     * @throws ValidationException
     */
    public function __construct($session, $data, $sessionKey)
    {
        $this->session = $session;

        /*
         * Fetch deferred Excel file
         */
        try {
            $file = $this->session->import_file()
                ->withDeferred($sessionKey)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ValidationException([
                Lang::get('elipce.limesurvey::lang.backend.sessions.no_file')
            ]);
        }
        /*
         * Load excel file data
         */
        $sheet = Excel::selectSheetsByIndex(0)
            ->load($file->getLocalPath())
            ->setSeparator(' ')
            ->all()
            ->first();

        $this->rows = $sheet;

        /*
         * New session
         */
        if (empty($session->id)) {

            $this->session->fill($data);
            $this->session->story_id = $data['story'];
            $this->session->validate([
                'story'        => 'required',
                'uid_column'   => 'required',
                'email_column' => 'required',
                'fn_column'    => 'required',
                'sn_column'    => 'required',
                'role_column'  => 'required'
            ]);
            /*
             * Generate session custom columns model collection
             */
            $this->sessionColumns = $this->generateColumns($data['custom_columns']);

        } else {
            /*
             * Fetch existing participants (UID)
             */
            $existing = $session->participants->lists('uid');
            /*
             * Exclude existing participants
             */
            $this->rows = $this->rows->filter(function($row) use ($session, $existing) {
                return ! in_array($row[$session->uid_column], $existing);
            });

            $this->sessionColumns = $this->session->session_columns;
        }
        /*
         * Fetch story mask columns
         */
        $columns = $this->session->story_columns()->get()->keyBy('name');
        /*
         * Merge story mask columns with session custom columns
         */
        $this->sessionColumns->each(function($c) use ($columns) {
            $columns->put($c->name, $c);
        });
        /*
         * Save merged columns
         */
        $this->allColumns = $columns;
        /*
         * Check Excel structure
         */
        $header = $sheet->first()->keys()->toArray();
        $this->validateStructure($header, $columns->lists('slug'));
    }

    /**
     * Generates column models from an array of data.
     *
     * @param $columns array
     * @return array
     */
    protected function generateColumns($columns)
    {
        return new Collection(
            array_map(function($c) {
                return new Column([
                    'name' => $c['column'],
                    'type' => $c['type']
                ]);
            }, $columns));
    }

    /**
     * Validate Excel structure file.
     *
     * @param array $header
     * @param array $columns
     * @throws ValidationException
     */
    protected function validateStructure($header, $columns)
    {
        /*
         * Excel file is empty
         */
        if (count($header) === 0) {
            throw new ValidationException([
                Lang::get('elipce.limesurvey::lang.backend.sessions.empty_file')
            ]);
        }
        $missingColumns = array_diff($columns, $header);
        /*
         * Check if required columns are found
         */
        if (count($missingColumns) > 0) {
            throw new ValidationException([
                Lang::get('elipce.limesurvey::lang.backend.sessions.columns_not_found')
                . implode(', ', $missingColumns)
            ]);
        }
    }

    /**
     * Returns session's columns to save.
     *
     * @return array
     */
    public function getColumnsToSave()
    {
        if ($this->session->id) {
            return [];
        }

        return $this->sessionColumns;
    }

    /**
     * Returns participant to save from Excel rows.
     *
     * @return array
     * @throws ValidationException
     */
    public function getParticipantsToSave()
    {
        $participantsToSave = [];

        /*
         * Fetch story roles
         */
        try {
            $roles = Story::findOrFail($this->session->story_id)->roles;

        } catch (ModelNotFoundException $e) {

            throw new ValidationException([
                Lang::get('elipce.limesurvey::lang.backend.sessions.story_not_found')
            ]);
        }
        /*
         * Browse excel rows
         */
        foreach ($this->rows as $index => $row) {
            /*
             * Ignore empty rows
             */
            if (count(array_filter($row->all())) <= 1) {
                continue;
            }
            /*
             * Find participant role
             */
            $code = $row[$this->session->role_column];
            $role = $roles->where('pivot.code', $code)->first();

            if (empty($role)) {
                throw new ValidationException([
                    Lang::get('elipce.limesurvey::lang.backend.sessions.role_not_found')
                    . ' (' . $row[$this->session->role_column] . ')'
                ]);
            }
            /*
             * Create participant model
             */
            $participant = new Participant([
                'uid'   => $row[$this->session->uid_column],
                'email' => $row[$this->session->email_column],
                'fn'    => $row[$this->session->fn_column],
                'sn'    => $row[$this->session->sn_column]
            ]);
            /*
             * Assign role
             */
            $participant->role_id = $role->id;
            /*
            * Check participant UID
            */
            if (array_key_exists($participant->uid, $participantsToSave)) {
                throw new ValidationException([
                    Lang::get('elipce.limesurvey::lang.backend.sessions.duplicated_participant')
                    . ($index + 2)
                ]);
            }
            try {
                /*
                 * Build attributes and validate them
                 */
                $attributesToSave = $this->allColumns->map(
                    function($c) use (&$row) {
                        $attribute = new Attribute([
                            'name'  => $c->name,
                            'value' => $row[$c->slug],
                            'type'  => $c->type
                        ]);
                        $attribute->validate();

                        return $attribute;
                    });
                /*
                 * Validate participant model
                 */
                $participant->validate();

            } catch (ValidationException $e) {
                throw new ValidationException([
                    Lang::get('elipce.limesurvey::lang.backend.sessions.invalid_participant')
                    . ($index + 2)
                ]);
            }
            /*
             * Defer participant custom attributes
             */
            $participant->bindEvent('model.afterCreate', function() use ($participant, $attributesToSave) {
                $participant->custom_attributes()->saveMany($attributesToSave);
            });

            $participantsToSave[$participant->uid] = $participant;
        }

        return $participantsToSave;
    }

    /**
     * Helper function to load participant columns.
     *
     * @param File|null $file
     * @return array
     */
    public static function loadParticipantColumns($file)
    {
        $columns = [];

        if ($file) {
            /*
             * Load Excel file
             */
            $excel = Excel::selectSheetsByIndex(0)
                ->load($file->getLocalPath())
                ->setSeparator(' ')
                ->all()->first();

            $columns = $excel->first()->keys()->toArray();
        }

        return $columns;
    }
}