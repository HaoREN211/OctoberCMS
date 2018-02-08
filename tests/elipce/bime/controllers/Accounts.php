<?php namespace Elipce\Bime\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Elipce\Bime\Classes\BimeService;
use Elipce\Bime\Models\Account;
use Elipce\BiPage\Models\Collection;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Lang;

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
    public $requiredPermissions = ['elipce.datasources.bime.access_accounts'];

    /**
     * Accounts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'bime_accounts');
    }

    /**
     * List AJAX controller to synchronize accounts.
     *
     * @return mixed
     */
    public function index_onSynchronize()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            Account::whereIn('id', $checkedIds)->get()->each(function($account) {
                /*
                 * Synchronize Bime account
                 */
                $bimeService = new BimeService($account);
                $bimeService->synchronize();
                /*
                 * Synchronize collection links
                 */
                Collection::all()->each(function($c) {
                    $c->touch();
                });
            });

            Flash::success(Lang::get('elipce.bime::lang.backend.messages.sync_success'));
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
        // Synchronize account data from Bime
        $bimeService = new BimeService($model);
        $bimeService->synchronize();
    }
}