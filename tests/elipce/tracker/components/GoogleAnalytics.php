<?php namespace Elipce\Tracker\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Exception\ApplicationException;

/**
 * Class GoogleAnalytics
 * @package Elipce\Tracker\Components
 */
class GoogleAnalytics extends ComponentBase
{

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'elipce.tracker::lang.components.ga',
            'description' => 'elipce.tracker::lang.components.ga_description'
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
     * Component validation
     * @throw ApplicationException
     */
    public function validate()
    {
        // Check if portal component is loaded
        if ($this->page['portal'] === null)
            throw new ApplicationException('Portal component not found !');
    }

    /**
     * On component run
     */
    public function onRun()
    {
        $this->validate();
    }

}