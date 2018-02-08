<?php namespace Elipce\Tableau\Classes;

use Elipce\BiPage\Classes\RestClientException;
use Elipce\Tableau\Models\Site;

class TableauAuth
{
    /**
     * Login to a site (token, site id and owner id),
     * Generate credentials
     *
     * @param Site $site
     * @return \stdClass
     * @throws RestClientException
     */
    public static function login(Site $site)
    {
        // Build XML request and REST client
        $xmlRequest = self::buildXMLAuthRequest(
            $site->login,
            $site->password,
            $site->url
        )->saveXML();

        // Create REST client
        $restClient = new TableauRestClient();
        // Send POST request
        $restClient->post('auth/signin', $xmlRequest);
        // Get XML response
        $response = $restClient->getResponse();

        // Return Std object (for more convenience)
        return (object)[
            'token' => (string)$response->credentials['token'],
            'siteId' => (string)$response->credentials->site['id'],
            'ownerId' => (string)$response->credentials->user['id']
        ];
    }

    /**
     * Logout to a site
     * @param Site $site
     */
    public static function logout(Site $site)
    {
        $restClient = new TableauRestClient([
            'headers' => [
                'X-Tableau-Auth: ' . $site->token
            ]
        ]);

        $restClient->post('auth/signout');
    }

    /**
     * Build the authentification XML request
     *
     * @param string $login
     * @param string $password
     * @param string $url
     *
     * @return \DOMDocument
     */
    private static function buildXMLAuthRequest($login, $password, $url)
    {
        // Create XML root element
        $domTree = new \DOMDocument('1.0', 'UTF-8');
        // Create tsRequest element
        $tsRequest = $domTree->createElement('tsRequest');
        $tsRequest = $domTree->appendChild($tsRequest);

        // Create credentials element
        $credentials = $domTree->createElement('credentials');
        $credentialsName = $domTree->createAttribute('name');
        $credentialsPassword = $domTree->createAttribute('password');

        // Fill credentials attributes and append them
        $credentialsName->value = $login;
        $credentialsPassword->value = $password;
        $credentials->appendChild($credentialsName);
        $credentials->appendChild($credentialsPassword);

        // Append credentials element to tsRequest element
        $credentials = $tsRequest->appendChild($credentials);

        // Create site element
        $site = $domTree->createElement('site');

        // Fill site attribute and apppend it
        $siteContentUrl = $domTree->createAttribute('contentUrl');

        $siteContentUrl->value = $url;
        $site->appendChild($siteContentUrl);

        // Append site element to credentials element
        $credentials->appendChild($site);

        return $domTree;
    }
}