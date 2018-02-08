<?php namespace Elipce\Analytics\Classes;

use Elipce\Analytics\Models\Account;
use Elipce\Analytics\Models\Metadata;
use Elipce\Analytics\Models\Property;
use Elipce\Analytics\Models\View;
use Illuminate\Support\Facades\File;
use \Google_Service_Analytics as GoogleServiceAnalytics;
use \Google_Auth_AssertionCredentials as GoogleAuthAssertionCredentials;
use \Google_Client as GoogleClient;

/**
 * Class AnalyticsService
 * @package Elipce\Analytics\Classes
 */
class AnalyticsService
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var GoogleServiceAnalytics
     */
    protected $apiService;

    /**
     * GoogleService constructor.
     * @param $account Account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * API Service getter
     * @return null|GoogleServiceAnalytics
     */
    protected function getApiService()
    {
        if (empty($this->apiService)) {
            $this->apiService = new GoogleServiceAnalytics($this->buildGoogleClient());
        }

        return $this->apiService;
    }

    /**
     * Build a Google client for this account
     * @return \Google_Client
     */
    protected function buildGoogleClient()
    {
        // Create Google client
        $client = new GoogleClient();
        $client->setApplicationName($this->account->name);

        // Get JSON security key
        $key = File::get($this->account->p12key->getLocalPath());

        // Set up crendentials
        $credentials = new GoogleAuthAssertionCredentials(
            $this->account->email, ['https://www.googleapis.com/auth/analytics.readonly'], $key);

        // Generate token
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($credentials);
        }

        return $client;
    }

    /**
     * Synchonize all data from Google Analytics API
     */
    public function synchronize()
    {
        $properties = [];
        $views = [];

        // Fetch Google Analytics accounts' summaries
        $summaries = $this->getApiService()
            ->management_accountSummaries
            ->listManagementAccountSummaries();

        // For each Google Analytics account
        foreach ($summaries->items as $account) {
            // For each current account's property
            foreach ($account->webProperties as $property) {
                // Update current property
                $p = Property::updateOrCreate([
                    'external_id' => $property->id,
                    'name' => $property->name,
                    'account_id' => $this->account->id
                ]);
                // Store it
                $properties[] = $p;
                // For each current property's view
                foreach ($property->profiles as $view) {
                    // Update current view
                    $v = View::updateOrCreate([
                        'external_id' => $view->id,
                        'name' => $view->name,
                        'type' => $view->type,
                        'property_id' => $p->id
                    ]);
                    // Store it
                    $views[] = $v;
                }
            }
        }

        // Delete old stuff
        Property::destroy($this->account->properties->diff($properties)->lists('id'));
        View::destroy($this->account->views->diff($views)->lists('id'));

        // Update timestamp
        $this->account->touch();
    }

    /**
     * Load Google Analytics metadata in database
     */
    public function loadMetadata()
    {
        // Fetch Google Analytics available metadata
        $metadata = $this->getApiService()
            ->metadata_columns->
            listMetadataColumns('ga');

        // Update metadata in database
        foreach ($metadata->items as $item) {
            Metadata::updateOrCreate([
                'name' => $item['attributes']['uiName'],
                'description' => $item['attributes']['description'],
                'type' => $item['attributes']['type'],
                'code' => $item['id']
            ]);
        }
    }

    /**
     * Generate access token
     * @return string
     */
    public function generateToken()
    {
        $apiService = $this->getApiService();
        return json_decode($apiService->getAccessToken())->access_token;
    }

}