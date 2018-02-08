<?php namespace Elipce\SSO\Http\Controllers;

use Elipce\SSO\Facades\SingleSignOnHelper;
use Elipce\SSO\Models\Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

/**
 * Class SingleLogoutServiceController
 * @package Elipce\SSO\Http\Controllers
 *
 * This code handles the Logout Request and the Logout Responses.
 *
 * Route : /sso/endpoints/sls
 *
 */
class SingleLogoutServiceController extends Controller
{
    /**
     * Return Attribute Consumer Service view
     * @throws \OneLogin_Saml2_Error
     */
    public function index()
    {
        $auth = new \OneLogin_Saml2_Auth(SingleSignOnHelper::getSettings());

        /*
         * Retrieve the last logout request ID.
         */
        if (Session::has('LogoutRequestID')) {
            $requestID = Session::get('LogoutRequestID');
        } else {
            $requestID = null;
        }

        /*
         * Retrieve redirect URL.
         */
        if (Session::has('LogoutRequestURL')) {
            $redirect_url = Session::get('LogoutRequestURL');
        } else {
            $redirect_url = SingleSignOnHelper::getBaseUrl();
        }

        /*
         * Handles the SAML response.
         */
        $auth->processSLO(false, null);
        $errors = $auth->getErrors();

        /*
         * Check errors.
         */
        if (!empty($errors)) {
            throw new \OneLogin_Saml2_Error(
                'Invalid SLS: ' . implode(', ', $errors)
            );
        }

        /*
         * Logout portal's user and redirect.
         */
        Auth::logout();
        return Redirect::to($redirect_url);
    }
}
