<?php namespace Elipce\SSO\Facades;

use October\Rain\Support\Facade;

/**
 * Class SingleSignOnHelper
 * @package Elipce\SSO\Facades
 */
class SingleSignOnHelper extends Facade
{
    /**
     * Get the binding in the IoC container
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sso.helper';
    }
}