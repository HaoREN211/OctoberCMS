<?php namespace Elipce\SSO\Http\Controllers;

use Illuminate\Routing\Controller;
use Elipce\SSO\Facades\SingleSignOnHelper;
use Illuminate\Support\Facades\Response;

/**
 * Class MetadataController
 * @package Elipce\SSO\Http\Controllers
 *
 * This code will provide the XML metadata file of our SP,
 * based on the info that we provided in the settings files.
 *
 * Route : /sso/endpoints/metadata
 *
 */
class MetadataController extends Controller
{

    /**
     * Return metadata view
     */
    public function index()
    {
        try {
            /*
             * Generate Metadata XML
             */
            $settingsInfo = SingleSignOnHelper::getSettings();
            $auth = new \OneLogin_Saml2_Auth($settingsInfo);
            $settings = $auth->getSettings();
            $metadata = $settings->getSPMetadata();
            $errors = $settings->validateMetadata($metadata);

            /*
             * Check errors.
             */
            if (empty($errors)) {
                /*
                 * Send XML Metadata
                 */
                return Response::make($metadata, '200')
                    ->header('Content-Type', 'text/xml');
            } else {
                throw new \OneLogin_Saml2_Error(
                    'Invalid SP metadata: ' . implode(', ', $errors),
                    \OneLogin_Saml2_Error::METADATA_SP_INVALID
                );
            }
        } catch (\Exception $e) {
            return Response::make($e->getMessage(), '200')
                ->header('Content-Type', 'text/html');
        }
    }
}