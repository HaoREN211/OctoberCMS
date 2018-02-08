<?php namespace Elipce\Bime\Classes;

use Elipce\BiPage\Classes\RestClient;
use Elipce\BiPage\Classes\RestClientException;
use Illuminate\Support\Collection;

/**
 * Class BimeRestClient
 * @package Elipce\Bime\Classes
 * REST client for Bime API
 */
class BimeRestClient extends RestClient
{
    /**
     * Extend constructor
     * @param array $options
     */
    public function __construct($options)
    {
        parent::__construct(array_merge([
            'base_url' => 'https://api.bime.io/v3'
        ], $options));
    }

    /**
     * Extend request validation
     * @throws RestClientException
     */
    protected function validateResponse()
    {
        // Empty response
        if(empty($this->response['result'])) {
            $this->error = 'Response does not contain any data.';
            throw new RestClientException($this->error);
        }
        // Error response
        if ($this->response['error'] != null) {
            $this->error = $this->response['error'];
            throw new RestClientException($this->error);
        }
    }

    /**
     * Extend response getter
     * @return \Illuminate\Support\Collection
     */
    public function getResponse()
    {
        $result = $this->response['result'];

        // String result
        if (! is_array($result)) {
            return $result;
        }

        // One record
        if (! is_array(reset($result))) {
            return $result;
        }

        // Format data fields
        foreach ($result as &$array) {
            // Rename id key to avoid merging conflicts
            $array['external_id'] = $array['id'];
            unset($array['id']);
            // Delete timestamp
            unset($array['created_at']);
            unset($array['updated_at']);
        }

        return Collection::make($result);
    }
}