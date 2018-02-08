<?php namespace Elipce\Analytics\Controllers;

use Elipce\Analytics\Classes\AnalyticsService;
use October\Rain\Support\Facades\Flash;
use Elipce\Analytics\Models\Account;
use Elipce\Analytics\Models\Metadata;
use Illuminate\Support\Facades\Lang;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

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
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.datasources.analytics.access_accounts'];

    /**
     * Accounts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'analytics_accounts');
    }

    /**
     * List AJAX handler to synchronize accounts.
     */
    public function index_onSynchronize()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            Account::whereIn('id', $checkedIds)->get()->each(function($account) {
                $analyticsService = new AnalyticsService($account);
                $analyticsService->synchronize();
            });

            Flash::success(Lang::get('elipce.analytics::lang.backend.messages.sync_success'));
        }

        return $this->listRefresh();
    }

    /**
     * Called after the creation form is saved.
     *
     * @param Account $model
     */
    public function formAfterCreate($model)
    {
        /*
         * Load Google Analytics metrics and dimensions
         */
        if (Metadata::all()->count() == 0) {
            $analyticsService = new AnalyticsService($model);
            $analyticsService->loadMetadata();
        }
    }
}