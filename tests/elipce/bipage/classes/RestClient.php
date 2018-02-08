<?php namespace Elipce\BiPage\Classes;

use October\Rain\Exception\ApplicationException;

/**
 * Class RestClientException
 * @package Elipce\BiPage\Classes
 */
class RestClientException extends ApplicationException
{
}

class RestClient
{
    /**
     * Parameters
     * @var
     */
    protected $url;
    protected $options;

    /**
     * Response values
     * @var
     */
    protected $response;
    protected $info;
    protected $error;

    /**
     * Constructor
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = [
            'headers' => [],
            'parameters' => [],
            'base_url' => null,
            'max_attempts' => 1,
            'attempt_int' => 2,
            'curl_options' => [],
            'user_agent' => 'PHP RestClient/0.1'
        ];

        if (count($options) > 0) {
            $this->options = array_merge($this->options, $options);
        }
    }

    /**
     * Options setter
     *
     * @param $key
     * @param $value
     * @throws RestClientException
     */
    public function setOption($key, $value)
    {
        if (! array_key_exists($key, $this->options)) {
            throw new RestClientException('Unknown option !');
        } else {
            $this->options[$key] = $value;
        }
    }

    /**
     * Get Resource
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function get($url, $parameters = [])
    {
        return $this->execute($url, 'GET', $parameters);
    }

    /**
     * Post Resource
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function post($url, $parameters = [])
    {
        return $this->execute($url, 'POST', $parameters);
    }

    /**
     * Put Resource
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function put($url, $parameters = [])
    {
        return $this->execute($url, 'PUT', $parameters);
    }

    /**
     * Delete Resource
     * @param $url
     * @param array $parameters
     * @return mixed
     */
    public function delete($url, $parameters = [])
    {
        return $this->execute($url, 'DELETE', $parameters);
    }

    /**
     * Execute REST request
     *
     * @param string $url
     * @param string $method
     * @param array $parameters
     *
     * @return RestClient
     * @throws RestClientException
     */
    protected function execute($url, $method = 'GET', $parameters = [])
    {
        // Get curl object
        $curl = curl_init();
        $this->url = $url;

        // Create options
        $curlopt = [
            CURLOPT_HTTPHEADER => $this->options['headers'],
            CURLOPT_USERAGENT => $this->options['user_agent'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ];

        // Allow passing parameters as a pre-encoded string
        $parameters_string = (is_array($parameters)) ?
            http_build_query($parameters) : (string) $parameters;

        // Handle parameters
        if (strtoupper($method) == 'POST') {
            $curlopt[CURLOPT_POST] = count($parameters);
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        } elseif (strtoupper($method) != 'GET') {
            $curlopt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        } elseif ($parameters_string) {
            $this->url = strpos($this->url, '?') ? '&' : '?';
            $this->url .= $parameters_string;
        }

        // Build entire URL
        if ($this->options['base_url']) {
            if ($this->url[0] != '/' && substr($this->options['base_url'], -1) != '/')
                $this->url = '/' . $this->url;
            $this->url = $this->options['base_url'] . $this->url;
        }

        // Configure and execute request
        $curlopt[CURLOPT_URL] = $this->url;
        curl_setopt_array($curl, $curlopt);

        // Init loop vars
        $attempts = 0;
        $continue = true;

        // Attempts
        while ($continue && $attempts <= $this->options['max_attempts']) {
            try {
                // Execute request and get response
                $this->response = $this->decode(curl_exec($curl));
                // Validate response
                $this->validateResponse();
                $continue = false;
            } catch (RestClientException $e) {
                $attempts++;
                // Wait between attempts
                sleep(pow($this->options['attempt_int'], $attempts));
            }
        }

        // Throw error
        if ($continue)
            throw new RestClientException($this->error);

        // Get messages
        $this->info = (object) curl_getinfo($curl);
        $this->error = curl_error($curl);

        // Close resource
        curl_close($curl);

        return $this;
    }

    /**
     * Validate the response (to extend)
     * @throws RestClientException
     */
    protected function validateResponse()
    {
    }

    /**
     * Return request result
     *
     * @return mixed
     * @throws RestClientException
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Return request error
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Return request informations
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Decode response
     *
     * @param $response
     * @return mixed
     */
    protected function decode($response)
    {
        return json_decode($response, true);
    }
}