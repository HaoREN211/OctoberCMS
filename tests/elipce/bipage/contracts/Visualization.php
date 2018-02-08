<?php namespace Elipce\BiPage\Contracts;

use Elipce\BiPage\Models\Page;
use RainLab\User\Models\User;

/**
 * Interface Visualization
 * @package Elipce\BiPage\Classes
 */
interface Visualization
{
    /**
     * Check if user can access to the visualization
     * @param User $user
     * @return mixed
     */
    public function canAccess(User $user = null);

    /**
     * Build visualization partial parameters
     * @param Page $page
     * @param User $user
     * @return string
     */
    public function buildPartialParameters(Page $page, User $user = null);

    /**
     * Return visualization title
     * @return string
     */
    public function getTitle();

    /**
     * Return visualization type
     * @return string
     */
    public function getType();

}