<?php namespace Elipce\Multisite\Behaviors;

use Backend\Facades\Backend;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Extension\ExtensionBase;
use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use Backend\Models\User;

/**
 * Class RedirectionController
 * @package Elipce\BiPage\Behaviors
 */
class RedirectionController extends ExtensionBase
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
     * Extend index list controller behavior
     */
    public function index()
    {
        // Redirect user to his portal
        if (! $this->user->isSuperUser()) {

            // Retrieve config
            $config = $this->controller
                ->makeConfig($this->controller->listConfig);

            // Build redirect url
            $url = str_replace(':id', $this->user->portal->id, $config->recordUrl);
            return Redirect::to(Backend::url($url));
        }

        // Call the ListController behavior index() method
        return $this->controller->asExtension('ListController')->index();
    }

}