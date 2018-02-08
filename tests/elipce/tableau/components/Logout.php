<?php namespace Elipce\Tableau\Components;

use Cms\Classes\ComponentBase;

/**
 * Class Logout
 * @package Elipce\Tableau\Components
 */
class Logout extends ComponentBase
{

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.tableau::lang.components.logout',
            'description' => 'elipce.tableau::lang.components.logout_description'
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

}