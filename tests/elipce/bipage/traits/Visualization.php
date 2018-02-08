<?php namespace Elipce\BiPage\Traits;

/**
 * Trait Visualization
 * @package Elipce\BiPage\Traits
 */
trait Visualization
{

    /**
     * Add observer
     */
    public static function bootVisualization()
    {
        static::observe(new VisualizationObserver());
    }

}