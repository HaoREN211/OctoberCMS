<?php namespace Elipce\Multisite\Components;

use Cms\Classes\ComponentBase;
use Elipce\Multisite\Facades\Multisite;

/**
 * Class Domain
 * Load domain in page data
 * @package Elipce\Multisite\Components
 */
class Portal extends ComponentBase
{
    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'elipce.multisite::lang.components.portal',
            'description' => 'elipce.multisite::lang.components.portal_description'
        ];
    }

    /**
     * Component properties
     * @return array
     */
    public function defineProperties()
    {
        return [];
    }

    /**
     * On component run
     */
    public function onRun()
    {
        $this->page['portal'] = Multisite::getPortal();
    }

}