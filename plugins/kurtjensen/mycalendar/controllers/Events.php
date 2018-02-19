<?php namespace KurtJensen\MyCalendar\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use KurtJensen\MyCalendar\Classes\RRValidator;
use KurtJensen\MyCalendar\Models\Settings;

/**
 * Events Back-end Controller
 */
class Events extends Controller {

	public $implement = [
		'Backend.Behaviors.FormController',
		'Backend.Behaviors.ListController',
	];

	public $formConfig = 'config_form.yaml';
	public $listConfig = 'config_list.yaml';

	public function __construct() {
		parent::__construct();

		BackendMenu::setContext('KurtJensen.MyCalendar', 'mycalendar', 'events');
		$this->addJs(Settings::get('boostrap_cdn', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'));
	}

	public function update_onSave($id) {
		$error = $this->validate();
		return $error ?: parent::update_onSave($id);
	}

	public function create_onSave() {
		$error = $this->validate();
		return $error ?: parent::create_onSave();
	}

	public function validate() {
		$RValidator = new RRValidator();
		if (!$RValidator->valid(post())) {
			// Sets a warning message
			return Flash::error(implode(',', $RValidator->messages->all()));
		}
		return false;
	}
}