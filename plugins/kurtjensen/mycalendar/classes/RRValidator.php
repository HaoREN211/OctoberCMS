<?php namespace KurtJensen\MyCalendar\Classes;

use Lang;
use Validator;

class RRValidator {

	public $messages;

	public function __construct() {
	}

	public function valid($formValues) {
		$v = [];
		$freq = strtoupper(array_get($formValues, 'FREQ'));
		switch ($freq) {
		case 'None':
			break;
		case 'WEEKDAYS':
			break;
		case 'WEEKENDS':
			break;
		case 'HOURLY':$v['INTERVAL'] = 'required|integer|min:1';
			break;
		case 'DAILY':$v['INTERVAL'] = 'required|integer|min:1';
			break;
		case 'WEEKLY':
			$v['INTERVAL'] = 'required|integer|min:1';
			$v['WBYDAY'] = 'required|array';
			break;
		case 'MONTHLY':
			$v['INTERVAL'] = 'required|integer|min:1';
			if (!array_get($formValues, 'month_on') == 'on_day') {
				$v['BYSETPOS'] = 'required|max:5|min:-5';
				$v['BYDAY'] = 'required|array';
			}
			break;
		case 'YEARLY':
			$v['INTERVAL'] = 'required|integer|min:1';
			if (array_get($formValues, 'year_on', '') == 'on_the') {
				$v['YBYMONTH'] = 'required';
				$v['YBYDAY'] = 'required';
				$v['YBYSETPOS'] = 'required';
			}
			break;
		case 'SERIES':
			$v['INTERVALS'] = 'required|min:3';
			break;
		}
		if (isset($v['INTERVAL']) || isset($v['INTERVALS'])) {
			$ends = strtoupper(array_get($formValues, 'Ends'));
			if ($ends == 'AFTER') {
				$v['COUNT'] = 'required|max:100|min:1';
			} elseif ($ends == 'DATE') {
				$v['ENDON'] = 'required|date_format:"Y-m-d"';
			}
		}

		$validator = Validator::make($formValues, $v, Lang::get('kurtjensen.mycalendar::validation'));

		if ($validator->fails()) {
			$this->messages = $validator->messages();
			return false;
		}
		return true;
	}
}
