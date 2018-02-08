<?php namespace Elipce\LimeSurvey\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Elipce\Multisite\Models\Portal;
use October\Rain\Database\Model;

/**
 * Accounts Back-end Controller
 */
class Accounts extends Controller
{

    /**
     * @var array Behaviors
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
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.limesurvey.access_accounts'];

    /**
     * Accounts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.LimeSurvey', 'limesurvey', 'accounts');
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
         * Add dynamic method for portals checkbox list
         */
        $model->addDynamicMethod('getPortalsOptions', function() use ($model, $user) {
            /*
             * Allow only free portal
             */
            $query = Portal::isAllowed($user)->whereNull('limesurvey_account_id');
            /*
             * Update context, allow associated portals too
             */
            if ($model->id) {
                $query->orWhere(function($query) use ($model) {
                    $query->where('limesurvey_account_id', $model->id);
                });
            }

            return $query->lists('name', 'id');
        });

        return $model;
    }
}