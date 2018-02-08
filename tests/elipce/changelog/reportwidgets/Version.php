<?php namespace Elipce\Changelog\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

/**
 * System status report widget.
 *
 * @package october\system
 * @author Alexey Bobkov, Samuel Georges
 */
class Version extends ReportWidgetBase
{
    /**
     * Renders the widget.
     */
    public function render()
    {
        return $this->makePartial('widget');
    }

    /**
     * Properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'Version',
                'default'           => 'Version',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ]
        ];
    }
}
