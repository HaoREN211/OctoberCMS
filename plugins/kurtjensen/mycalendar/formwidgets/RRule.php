<?php namespace KurtJensen\MyCalendar\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * RRule
 * Renders RRule fields.
 *
 * @package kurtjensen\mycalendar
 * @author Kurt Jensen
 */
class RRule extends FormWidgetBase {
	use \KurtJensen\MyCalendar\Traits\RRuleWidget;
	/**
	 * {@inheritDoc}
	 */
	protected $defaultAlias = 'rrule';

	/**
	 * {@inheritDoc}
	 */
	public function render() {
		$pattern_string = $this->model->{$this->fieldName} ?: 'FREQ=DAILY;INTERVAL=1;COUNT=1;';
		$this->prepareVars($pattern_string);
		return $this->makePartial('rrule');
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadAssets() {
		//$this->addCss('css/rrule.css');
		$this->addJs('js/rrule.js');
	}
}