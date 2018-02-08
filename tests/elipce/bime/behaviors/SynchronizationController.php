<?php namespace Elipce\Bime\Behaviors;

use Backend\Models\User;
use Elipce\Bime\Classes\BimeService;
use Backend\Facades\BackendAuth;
use Elipce\Bime\Models\Account;
use Illuminate\Support\Facades\Lang;
use October\Rain\Extension\ExtensionBase;
use Backend\Classes\Controller;
use October\Rain\Support\Facades\Flash;

/**
 * Class SynchronizationController
 * @package Elipce\BiPage\Behaviors
 */
class SynchronizationController extends ExtensionBase
{

    /**
     * Reference to the extended object.
     * @var Controller
     */
    protected $controller;

    /**
     * Reference to the backend user.
     * @var User
     */
    protected $user;

    /**
     * Constructor
     * @var Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->user = BackendAuth::getUser();
    }

    /**
     * Synchronize AJAX action handler
     * @return mixed
     */
    public function update_onSynchronize($recordId = null)
    {
        /*
         * Get form model
         */
        $collection = $this->controller->formFindModelObject($recordId);

        /*
         * Synchronize related Bime accounts
         */
        Account::whereIn('id', $collection->bime_accounts)
            ->get()->each(function ($account) {
                $bimeService = new BimeService($account);
                $bimeService->synchronize();
            });

        /*
         * Update association links
         */
        $collection->touch();

        /*
         * Feedback message
         */
        Flash::success(Lang::get('elipce.bime::lang.backend.messages.sync_success'));

        /*
         * Refresh relations
         */
        $this->controller->initForm($collection);
        $this->controller->initRelation($collection, 'dashboards');
        $readOnly = !$this->user->hasAccess('elipce.bipage.access_collections');
        return $this->controller->relationRender('dashboards', ['readOnly' => $readOnly]);
    }

    /**
     * Provides an opportunity to manipulate the manage widget.
     * @param $widget
     */
    public function relationExtendManageWidget($widget)
    {
        // Filter relation manager list
        $widget->bindEvent('list.extendQuery', function ($query) {
            $query->where('type', '<>', 'Bime');
        });
    }

}