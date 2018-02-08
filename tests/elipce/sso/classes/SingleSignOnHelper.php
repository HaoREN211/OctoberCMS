<?php namespace Elipce\SSO\Classes;

use Elipce\SSO\Models\Settings as SAMLSettings;
use Illuminate\Support\Facades\Config;

/**
 * Class SingleSignOnHelperException
 * @package Elipce\SSO\Classes
 */
class SingleSignOnHelperException extends \Exception {}


/**
 * Class SingleSignOnHelper
 * @package Elipce\SSO\Classes
 */
class SingleSignOnHelper
{

    use \October\Rain\Support\Traits\Singleton;

    /**
     * Default SAML parameters
     * @var array
     */
    protected static $DEFAULT_SETTINGS = array (
        // If 'strict' is True, then the PHP Toolkit will reject unsigned
        // or unencrypted messages if it expects them signed or encrypted
        // Also will reject the messages if not strictly follow the SAML
        // standard: Destination, NameId, Conditions ... are validated too.
        'strict' => false,

        // Enable debug mode (to print errors)
        'debug' => false,

        // Service Provider Data that we are deploying
        'sp' => array (
            // Identifier of the SP entity  (must be a URI)
            'entityId' => '',
            // Specifies info about where and how the <AuthnResponse> message MUST be
            // returned to the requester, in this case our SP.
            'assertionConsumerService' => array (
                // URL Location where the <Response> from the IdP will be returned
                'url' => '',
                // SAML protocol binding to be used when returning the <Response>
                // message.  Onelogin Toolkit supports for this endpoint the
                // HTTP-Redirect binding only
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ),
            // Specifies constraints on the name identifier to be used to
            // represent the requested subject.
            // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:emailAddress',

            // Usually x509cert and privateKey of the SP are provided by files placed at
            // the certs folder. But we can also provide them with the following parameters
            'x509cert' => '',
            'privateKey' => '',
        ),

        // Identity Provider Data that we want connect with our SP
        'idp' => array (
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => '',
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array (
                // URL Target of the IdP where the SP will send the Authentication Request Message
                'url' => '',
                // SAML protocol binding to be used when returning the <Response>
                // message.  Onelogin Toolkit supports for this endpoint the
                // HTTP-POST binding only
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array (
                // URL Location of the IdP where the SP will send the SLO Request
                'url' => '',
                // SAML protocol binding to be used when returning the <Response>
                // message.  Onelogin Toolkit supports for this endpoint the
                // HTTP-Redirect binding only
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // Public x509 certificate of the IdP
            'x509cert' => '',
            /*
             *  Instead of use the whole x509cert you can use a fingerprint
             *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
             *   or add for example the -sha256 , -sha384 or -sha512 parameter)
             *
             *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
             *  let the toolkit know which Algorithm was used. Possible values: sha1, sha256, sha384 or sha512
             *  'sha1' is the default value.
             */
            // 'certFingerprint' => '',
            // 'certFingerprintAlgorithm' => 'sha1',
        ),
    );

    /**
     * Custom SAML parameters
     * @var array
     */
    protected $settings;

    /**
     * Service provider base URL
     * @var string
     */
    protected $baseUrl;


    /**
     * Initialize singleton
     */
    protected function init()
    {
        $this->baseUrl = Config::get('app.url');
        $this->settings = $this->buildSettings();
    }

    /**
     * Build custom SAML settings according to plugin settings
     * @return array
     */
    protected function buildSettings()
    {
        // Load default settings
        $settings = self::$DEFAULT_SETTINGS;

        // Complete service provider parameters
        $settings['sp']['entityId'] = "{$this->baseUrl}/sso/endpoints/metadata";
        $settings['sp']['assertionConsumerService']['url'] = "{$this->baseUrl}/sso/endpoints/acs";
        $settings['sp']['singleLogoutService']['url'] = "{$this->baseUrl}/sso/endpoints/sls";

        // Complete identity service provider parameters
        $settings['idp']['entityId'] = SAMLSettings::get('idp_id', null);
        $settings['idp']['singleSignOnService']['url'] = SAMLSettings::get('idp_acs', null);
        $settings['idp']['singleLogoutService']['url'] = SAMLSettings::get('idp_sls', null);
        $settings['idp']['x509cert'] = SAMLSettings::get('idp_x509cert', null);

        // Set debug option
        $settings['debug'] = SAMLSettings::get('debug', false);

        return $settings;
    }

    /**
     * Return service provider base url
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Settings getter
     *
     * @param $path
     * @return array
     * @throws SingleSignOnHelperException
     */
    public function getSettings($path = null)
    {
        $res = $this->settings;

        if ($path != null) {
            $path = explode('.', $path);

            foreach($path as $key) {
                if (array_key_exists($key, $res)) {
                    $res = $res[$key];
                } else {
                    throw new SingleSignOnHelperException();
                }
            }
        }

        return $res;
    }

}