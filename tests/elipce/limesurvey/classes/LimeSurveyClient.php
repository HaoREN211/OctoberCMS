<?php namespace Elipce\LimeSurvey\Classes;

/**
 * Class LimeSurveyClientException
 * @package Elipce\LimeSurvey\Classes
 */
class LimeSurveyClientException extends \Exception
{
}

/**
 * Class LimeSurveyClient
 * @package Elipce\LimeSurvey\Classes
 */
class LimeSurveyClient
{

    /**
     * Error codes
     */
    const LIMESURVEY_XMLRPC_ERROR = 1;
    const LIMESURVEY_CURL_ERROR = 2;
    const LIMESURVEY_SESSION_KEY_ERROR = 3;

    /**
     * API session key
     * @var null|int
     */
    protected $sessionKey = null;

    /**
     * The cURL resource
     * @var mixed
     */
    protected $curl = null;

    /**
     * Full URL to the LimeSurvey API
     * @var string
     */
    public $url;

    /**
     * Port to use for LimeSurvey API
     * @var int
     */
    public $port;

    /**
     * Charset encoding
     * @var string
     */
    public $encoding = 'utf-8';

    /**
     * Enable / disable debug mode
     * @var mixed
     */
    public $debug = true;

    /**
     * Last cURL request trace log
     * @var array
     */
    public $logTrace = [];

    /**
     * LimeSurveyClient constructor.
     *
     * @param $host
     * @param $port
     */
    public function __construct($host, $port = 80)
    {
        $url = parse_url($host);
        $this->url = $url['scheme'] . '://' . $url['host'] . $url['path'];
        /*
         * Find port
         */
        if ($url['scheme'] === 'https') {
            $this->port = 443;
        } elseif (array_key_exists('port', $url)) {
            $this->port = $url['port'];
        } else {
            $this->port = $port;
        }
    }

    /**
     * API methods proxy
     *
     * @param $method string The API method name.
     * @param $arguments array The arguments of the method.
     * @return mixed
     *
     * @throws LimeSurveyClientException
     */
    function __call($method, $arguments)
    {
        // Auto bind session key if available
        if ($this->sessionKey)
            array_unshift($arguments, $this->sessionKey);

        // Encode xml-rpc request
        $xml = xmlrpc_encode_request($method, $arguments);

        // Initialize cURL
        if (empty($this->curl)) {
            $this->init();
        }

        // Put XML parameters
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->buildHeader($xml));
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $xml);

        // Send POST request
        $response = curl_exec($this->curl);

        // Check cURL errors
        if (curl_errno($this->curl)) {
            throw new LimeSurveyClientException(
                curl_error($this->curl),
                self::LIMESURVEY_CURL_ERROR
            );
        }

        // Decode response
        $result = xmlrpc_decode($response, $this->encoding);

        // Trace log
        if ($this->debug) {
            $this->logTrace = (object)[
                'request' => $xml,
                'response' => $response,
                'result' => $result,
                'curl' => curl_getinfo($this->curl)
            ];
        }

        // Check XML-RPC errors
        if ($result && is_array($result) && xmlrpc_is_fault($result)) {
            throw new LimeSurveyClientException(
                $result['faultString'],
                self::LIMESURVEY_XMLRPC_ERROR
            );
        }

        return $result;
    }

    /**
     * Initialize cURL resource
     */
    protected function init()
    {
        // Create cURL resource
        $this->curl = curl_init();

        // Configure url
        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        // Configure port
        curl_setopt($this->curl, CURLOPT_PORT, $this->port);

        // Configure other options
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_POST, true);
    }

    /**
     * Build cURL header.
     *
     * @param $xml
     * @return array
     */
    protected function buildHeader($xml)
    {
        return [
            'Content-type: text/xml',
            'Content-length: ' . strlen($xml)
        ];
    }

    /**
     * Trace the last cURL request log.
     */
    public function traceLog()
    {
        print_r($this->logTrace, true);
    }

    /**
     * Log trace getter.
     * @return array
     */
    public function getLogTrace()
    {
        return $this->logTrace;
    }

    /**
     * Port getter.
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * URL getter.
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Charset encoding getter.
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Charset encoding setter.
     * @param $type string
     */
    public function setEncoding($type)
    {
        $this->encoding = $type;
    }

    /**
     * Debug mode setter.
     * @param $bool
     */
    public function setDebug($bool)
    {
        $this->debug = $bool;
    }

    /**
     * Login API
     *
     * @param $user
     * @param $password
     * @throws LimeSurveyClientException
     */
    public function login($user, $password)
    {
        $result = $this->get_session_key($user, $password);

        if (is_array($result)) {
            throw new LimeSurveyClientException(
                $result['status'],
                self::LIMESURVEY_SESSION_KEY_ERROR
            );
        }

        $this->sessionKey = $result;
    }

    /**
     * Logout API
     */
    public function logout()
    {
        $this->release_session_key();
    }

    /**
     * Close cURL connection
     */
    public function close()
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
        }

        $this->curl = null;
    }

    /**
     * Automatic logout
     */
    function __destruct()
    {
        try {
            if (!empty($this->sessionKey)) {
                $this->logout();
            }
        } catch (LimeSurveyClientException $e) {
            // Unable to release the session key...
        } finally {
            $this->close();
        }
    }

}