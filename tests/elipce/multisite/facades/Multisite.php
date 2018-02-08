<?php namespace Elipce\Multisite\Facades;

use October\Rain\Support\Facade;

/**
 * Class Multisite
 * @package Elipce\Multisite\Facades
 */
class Multisite extends Facade
{
    /**
     * Get the binding in the IoC container
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'multisite.portal';
    }
}