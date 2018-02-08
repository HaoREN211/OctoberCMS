<?php namespace Elipce\Multisite\Classes;

use Illuminate\Support\Facades\Request;
use Elipce\Multisite\Models\Portal;

/**
 * Class MultisiteException
 * @package Elipce\Multisite\Classes
 */
class MultisiteException extends \Exception {}

/**
 * Class Multisite
 * Portal router
 */
class Multisite
{

    use \October\Rain\Support\Traits\Singleton;

    /**
     * Current URL
     * @var string
     */
    protected $url;

    /**
     * Portals cache
     * @var array
     */
    protected $portals;

    /**
     * Current portal
     * @var Portal
     */
    protected $portal;

    /**
     * Initialize singleton
     */
    public function init()
    {
        $this->url = Request::getHost();
        $this->portals = Portal::getCache();
        $this->run();
    }

    /**
     * Run the router process
     */
    public function run()
    {
        $match = null;

        // Find current portal
        foreach($this->portals as $portal) {
            if ($portal['host'] == $this->url) {
                $match = $portal;
                break;
            }
        }

        // No portal found
        if (empty($match))
            throw new MultisiteException('No portal found !');

        // Fetch matched portal
        $this->portal = Portal::find($match['id']);
    }

    /**
     * Return the current portal
     * @return mixed
     */
    public function getPortal()
    {
        return $this->portal;
    }
}