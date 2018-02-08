<?php namespace Elipce\Multisite\Behaviors;

use October\Rain\Extension\ExtensionBase;
use October\Rain\Database\Model;
use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use Backend\Widgets\Form;
use Backend\Models\User;

/**
 * Class PortalController
 * @package Elipce\BiPage\Behaviors
 */
class PortalController extends ExtensionBase
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
     * Overriding form fields
     *
     * @param $form Form
     * @param $fields
     */
    public function formExtendFields($form, $fields)
    {
        /*
         * Hide portal form field
         */
        if (! $this->user->isSuperUser()) {
            $form->removeField('portal');
            $form->removeField('portal_section');
            $form->removeField('portal[name]');
        }

        /*
         * Original FormController behavior method
         */
        $this->controller->asExtension('FormController')->formExtendFields($form, $fields);
    }

    /**
     * Overriding form model
     *
     * @param $model
     * @return Model
     */
    public function formExtendModel($model)
    {
        /*
         * Auto associate with backend user's portal
         */
        if (! $this->user->isSuperUser() && ! empty($this->user->portal_id)) {
            $model->portal()->associate($this->user->portal_id);
        }

        /*
        * Original FormController behavior method
        */
        return $this->controller->asExtension('FormController')->formExtendModel($model);
    }

}