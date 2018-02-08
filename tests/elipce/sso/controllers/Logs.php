<?php namespace Elipce\SSO\Controllers;

use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Lang;
use System\Classes\SettingsManager;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Elipce\SSO\Models\Log;

/**
 * Logs Back-end Controller
 */
class Logs extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    /**
     * Configuration files and permissions
     * @var array
     */
    public $requiredPermissions = ['system.access_logs'];
    public $listConfig = 'config_list.yaml';

    /**
     * EventLogs constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Elipce.SSO', 'logs');
    }

    /**
     * List AJAX handler to refresh data.
     * @return mixed
     */
    public function index_onRefresh()
    {
        return $this->listRefresh();
    }

    /**
     * List AJAX handler to empty logs.
     * @return mixed
     */
    public function index_onEmptyLog()
    {
        Log::truncate();
        Flash::success(Lang::get('system::lang.event_log.empty_success'));
        return $this->listRefresh();
    }
}
