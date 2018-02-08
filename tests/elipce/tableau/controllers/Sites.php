<?php namespace Elipce\Tableau\Controllers;

use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Lang;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Elipce\Tableau\Models\Site;

/**
 * Accounts Back-end Controller
 */
class Sites extends Controller
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
    public $requiredPermissions = ['elipce.datasources.tableau.access_sites'];

    /**
     * Sites constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'tableau_sites');
    }

    /**
     * List AJAX handler to synchronize checked sites.
     */
    public function index_onSynchronize()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $id) {
                if (!$site = Site::find($id)) continue;
                $site->synchronize();
            }

            Flash::success(Lang::get('elipce.tableau::lang.backend.messages.sync_success'));
        }

        return $this->listRefresh();
    }
}