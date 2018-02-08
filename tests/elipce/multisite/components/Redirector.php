<?php namespace Elipce\Multisite\Components;

use Cms\Classes\ComponentBase;
use Elipce\Multisite\Facades\Multisite;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Exception\ApplicationException;

/**
 * Class Redirector
 * @package Elipce\Multisite\Components
 */
class Redirector extends ComponentBase
{
    /**
     * Details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.multisite::lang.components.redirector',
            'description' => 'elipce.multisite::lang.components.redirector_description'
        ];
    }

    /**
     * Properties
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
        // Check if user is connected
        if ($this->page['user'] === null)
            throw new ApplicationException('No connected user found !');
    }

    /**
     * Redirect user to his portal.
     * @trow ApplicationException
     */
    public function onRun()
    {
        $this->validate();
        $portal = $this->page['user']->portal;

        // Check portal
        if (! $portal) return null;

        // Avoid redirect loops
        if ($portal->id == Multisite::getPortal()->id)
            return null;

        // Make redirect
        return redirect($portal->url);
    }

}