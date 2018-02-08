<?php namespace Elipce\SSO\Components;

use Cms\Classes\ComponentBase;
use Elipce\SSO\Models\Settings as SAMLSettings;
use Elipce\SSO\Facades\SingleSignOnHelper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

/**
 * Class SingleSignOnButton
 * @package Elipce\SSO\Components
 */
class SingleSignOnButton extends ComponentBase
{

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.sso::lang.components.sso_button',
            'description' => 'elipce.sso::lang.components.sso_button_description'
        ];
    }

    /**
     * Component properties
     * @return array
     */
    public function defineProperties()
    {
        return [];
    }

    /**
     * On component run
     */
    public function onRun()
    {
        $this->page['sso'] = $this->checkSettings();
    }

    /**
     * Check if SAML settings are not empty
     */
    protected function checkSettings()
    {
        $idp_acs = SAMLSettings::get('idp_acs', null);
        $idp_sls = SAMLSettings::get('idp_sls', null);
        $idp_id = SAMLSettings::get('idp_id', null);
        return !empty($idp_acs) && !empty($idp_sls) && !empty($idp_id);
    }

    /**
     * Ajax Action : SSO
     */
    public function onSingleSignOn()
    {
        // Load SAML settings
        $settings = new \OneLogin_Saml2_Settings(SingleSignOnHelper::getSettings());
        $authRequest = new \OneLogin_Saml2_AuthnRequest($settings);

        // Save the request ID
        Session::put('AuthNRequestID', $authRequest->getId());
        Session::put('AuthNRequestURL', Config::get('app.url'));

        // Get string request
        $samlRequest = $authRequest->getRequest();

        // Add parameters
        $parameters = array('SAMLRequest' => $samlRequest);
        $parameters['RelayState'] = \OneLogin_Saml2_Utils::getSelfURLNoQuery();

        // Build IdP URL
        $idpData = $settings->getIdPData();
        $ssoUrl = $idpData['singleSignOnService']['url'];
        $url = \OneLogin_Saml2_Utils::redirect($ssoUrl, $parameters, true);

        // Redirect to IdP login form
        return Redirect::to($url);
    }

    /**
     * Ajax Action : SLS
     */
    public function onSingleLogout()
    {
        // Load SAML settings
        $settings = new \OneLogin_Saml2_Settings(SingleSignOnHelper::getSettings());
        $logoutRequest = new \OneLogin_Saml2_LogoutRequest($settings);

        // Save the request ID
        Session::put('LogoutRequestID', $logoutRequest->id);
        Session::put('LogoutRequestURL', Config::get('app.url'));

        // Get string request
        $samlRequest = $logoutRequest->getRequest();

        // Add parameters
        $parameters = array('SAMLRequest' => $samlRequest);
        $parameters['RelayState'] = \OneLogin_Saml2_Utils::getSelfURLNoQuery();

        // Build IdP URL
        $idpData = $settings->getIdPData();
        $slsUrl = $idpData['singleLogoutService']['url'];
        $url = \OneLogin_Saml2_Utils::redirect($slsUrl, $parameters, true);

        // Redirect to IdP login form
        return Redirect::to($url);
    }

}