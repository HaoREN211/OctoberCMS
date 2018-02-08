<?php namespace Elipce\SSO\Http\Controllers;

use Elipce\SSO\Facades\SingleSignOnHelper;
use Elipce\SSO\Models\Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;
use RainLab\User\Models\Settings as UserSettings;
use Elipce\SSO\Models\Settings as SAMLSettings;

/**
 * Class AttributeConsumerServiceController
 * @package Elipce\SSO\Http\Controllers
 *
 * This code handles the SAML response that the IdP
 * forwards to the SP through the user's client.
 *
 * Route : /sso/endpoints/acs
 *
 */
class AttributeConsumerServiceController extends Controller
{

    /**
     * Return Attribute Consumer Service view
     * @throws \OneLogin_Saml2_Error
     */
    public function index()
    {
        $auth = new \OneLogin_Saml2_Auth(SingleSignOnHelper::getSettings());

        /*
         * Retrieve the last authentication request ID
         */
        if (Session::has('AuthNRequestID')) {
            $requestID = Session::get('AuthNRequestID');
        } else {
            $requestID = null;
        }

        /*
         * Retrieve redirect URL
         */
        if (Session::has('AuthNRequestURL')) {
            $redirect_url = Session::get('AuthNRequestURL');
        } else {
            $redirect_url = SingleSignOnHelper::getBaseUrl();
        }

        /*
         * Handles the SAML response.
         */
        $auth->processResponse($requestID);
        $errors = $auth->getErrors();

        if (!empty($errors)) {
            throw new \OneLogin_Saml2_Error(
                'Invalid SP ACS: ' . implode(', ', $errors)
            );
        }

        /*
         * If not authenticated, do nothing
         */
        if (!$auth->isAuthenticated()) {
            Log::create([
                'ip' => Request::getClientIp(),
                'email' => null,
                'result' => 'failed'
            ]);
            die();
        }

        /*
         * Fetch remote user's attributes from IdP.
         */
        $userAttributes = $auth->getAttributes();
        $emailAttributeName = SAMLSettings::get('email', 'mail');
        $email = $userAttributes[$emailAttributeName][0];

        /*
         * Find a frontend user with the same email
         * or create it if not exist
         */
        if (!$user = User::findByEmail($email)) {
            if (SAMLSettings::get('selfregister', false)) {
                $user = $this->register($userAttributes);
            } else {
                Log::create([
                    'ip' => Request::getClientIp(),
                    'email' => $email,
                    'result' => 'not exist'
                ]);
            }
        }

        /*
         * Login the matched user and redirect.
         */
        Auth::login($user);

        /*
         * Log the attempt and redirect
         */
        Log::create([
            'ip' => Request::getClientIp(),
            'email' => $user->email,
            'result' => 'success'
        ]);

        return Redirect::to($redirect_url);
    }

    /**
     * Register SSO user
     * @param array $userAttributes
     */
    protected function register(array $userAttributes)
    {
        // Figure out the user activation mode
        $automaticActivation =
            UserSettings::get('activate_mode') == UserSettings::ACTIVATE_AUTO;

        // Generate random password
        $randomPassword = substr(md5(rand()), 0, 12);

        // Fetch attribute names
        $emailAttributeName = SAMLSettings::get('email', 'mail');
        $firstnameAttributeName = SAMLSettings::get('firstname', 'fn');
        $lastnameAttributeName = SAMLSettings::get('lastname', 'sn');

        // Format user credentials from Idp data
        $data = [
            'email' => $userAttributes[$emailAttributeName][0],
            'surname' => $userAttributes[$firstnameAttributeName][0],
            'name' => $userAttributes[$lastnameAttributeName][0],
            'password' => $randomPassword,
            'password_confirmation' => $randomPassword
        ];

        // Register the new user
        return Auth::register($data, $automaticActivation);
    }
}