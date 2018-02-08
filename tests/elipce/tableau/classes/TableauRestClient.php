<?php namespace Elipce\Tableau\Classes;

use Elipce\BiPage\Classes\RestClient;
use Elipce\BiPage\Classes\RestClientException;
use Illuminate\Support\Collection;

/**
 * Class TableauRestClient
 * @package Elipce\Tableau\Classes
 */
class TableauRestClient extends RestClient
{
    /**
     * Extend constructor
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct(array_merge_recursive([
            'base_url' => 'https://online.tableau.com/api/2.1/',
            'max_attempts' => 3,
            'attempt_int' => 4
        ], $options));
    }

    /**
     * Extend request validation
     * @throws RestClientException
     */
    protected function validateResponse()
    {
        if (isset($this->response->error)) {
            $this->error = $this->response->error->summary;
            $this->error .= " : {$this->response->error->detail}";
            throw new RestClientException($this->error);
        }
    }

    /**
     * Extend decode method to handle XML
     * Note : you can use xpath() method to access nodes
     *
     * @param \SimpleXMLElement $response
     * @return mixed
     */
    protected function decode($response)
    {
        $xml = simplexml_load_string($response);
        $xml->registerXPathNamespace('ts', 'http://tableau.com/api');
        return $xml;
    }

    /**
     * Extend response getter with XPath
     *
     * @param string $xpath
     * @param $subAttributes
     * @return static mixed
     * @throws RestClientException
     */
    public function getResponse($xpath = null, $subAttributes = [])
    {
        // No XPath
        if ($xpath === null) {
            return parent::getResponse();
        }

        return $this->format($this->response->xpath('//ts:' . $xpath), $subAttributes);
    }

    /**
     * Format XML to custom Collection
     *
     * @param $xml
     * @param $subAttributes
     * @return static
     * @throws RestClientException
     */
    protected function format($xml, $subAttributes)
    {
        // XML to PHP array
        $items = json_decode(json_encode($xml), true);
        $results = [];

        // Extract results
        foreach ($items as $item) {
            // Check node attributes
            if (!array_key_exists('@attributes', $item)) {
                throw new RestClientException('No attributes found.');
            }
            // Extract sub attributes
            foreach($subAttributes as $name => $value) {
                // Check sub attribute existence
                if (!array_key_exists($name, $item)) {
                    throw new RestClientException("Attribute '$name' not found.");
                }
                // Copy sub attribute to attributes
                $attributeName = implode('_', [$name, $value]);
                $item['@attributes'][$attributeName] = $item[$name]['@attributes'][$value];
            }
            // Rename id key to avoid merging conflicts
            $item['@attributes']['external_id'] = $item['@attributes']['id'];
            unset($item['@attributes']['id']);
            $results[] = $item['@attributes'];
        }
        // Create collection
        return Collection::make($results);
    }
}