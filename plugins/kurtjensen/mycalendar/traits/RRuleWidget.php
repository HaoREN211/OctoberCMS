<?php namespace KurtJensen\MyCalendar\Traits;

/**
 * RRule
 * Renders RRule fields.
 *
 * @package kurtjensen\mycalendar
 * @author Kurt Jensen
 */
use Form;
use KurtJensen\MyCalendar\Models\Settings;
use Lang;

trait RRuleWidget {
	public $langPath = 'kurtjensen.mycalendar::lang.rrule.';

	public function t($string) {
		return Lang::get($this->langPath . $string);
	}

	public function maxCount() {
		return Settings::get('max_count');
	}

	/**
	 * Prepares the field data
	 */
	public function prepareVars($pattern_string) {
		$this->vars['f'] = $this->parseRrule($pattern_string);
		$this->vars['name'] = $this->formField->getName();
	}

	/**
	 * Process the postback data for this widget.
	 * @param $value The existing value for this widget.
	 * @return string The new value for this widget.
	 */

	public function getSaveValue($value) {
		$freq = strtoupper(post('FREQ'));
		$rrule = 'FREQ=' . $freq . ';';
		switch ($freq) {
		case 'NONE':
			return 'FREQ=DAILY;INTERVAL=1;COUNT=1;';
			break;
		case 'HOURLY':
			$rrule .= 'INTERVAL=' . post('INTERVAL') . ';'; //RDATE=FREQ=HOURLY;INTERVAL=1;UNTIL=2016-03-16;
			break;
		case 'DAILY':
			$rrule .= 'INTERVAL=' . post('INTERVAL') . ';'; // RDATE=FREQ=DAILY;INTERVAL=1;COUNT=1;
			break;
		case 'WEEKDAYS':
			$rrule = 'FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,TU,WE,TH,FR;WKST=SU;';
			break;
		case 'WEEKENDS':
			$rrule = 'FREQ=WEEKLY;INTERVAL=1;BYDAY=SA,SU;WKST=SU;';
			break;
		case 'WEEKLY':
			$rrule .= 'INTERVAL=' . post('INTERVAL') . ';';
			if (count(post('WBYDAY')) > 0) {
				$BYDAY = implode(',', post('WBYDAY'));
				$rrule .= 'BYDAY=' . $BYDAY . ';'; //FREQ=WEEKLY;BYDAY=SU,WE,TH;INTERVAL=3;COUNT=6
			}
			if (post('WBYDAY') && post('INTERVAL') > 1) {
				$rrule .= 'WKST=SU;';
			}
			break;
		case 'MONTHLY':
			$rrule .= 'INTERVAL=' . post('INTERVAL') . ';';

			if (post('month_on') == 'on_day') {
				list($y, $m, $d) = explode('-', post('date'));
				$rrule .= 'BYMONTHDAY=' . $d . ';';

			} else {
				$rrule .= 'BYSETPOS=' . post('MBYSETPOS') . ';BYDAY=' . post('MBYDAY') . ';';
			}
			break;
		case 'YEARLY':
			$rrule .= 'INTERVAL=' . post('INTERVAL') . ';';

			if (post('year_on') == 'on_day') {
				list($y, $m, $d) = explode('-', post('date'));
				$rrule .= 'BYMONTH=' . $m . ';BYMONTHDAY=' . $d . ';';

			} else {
				$rrule .= 'BYMONTH=' . post('YBYMONTH') . ';BYSETPOS=' . post('YBYSETPOS') . ';BYDAY=' . post('YBYDAY') . ';';
			}
			if (post('BYWEEKNO')) {
				$rrule .= 'WKST=SU;';
			}

			break;
		case 'SERIES':
			$rrule .= 'INTERVALS=' . post('INTERVALS') . ';';
			break;
		}

		$ends = strtoupper(post('Ends'));
		if ($ends == 'AFTER') {
			$rrule .= 'COUNT=' . post('COUNT') . ';';
		} elseif ($ends == 'DATE') {
			$rrule .= 'UNTIL=' . post('ENDON') . ';'; // UNTIL=20000131T090000Z;

		} else {
			$rrule .= 'COUNT=' . Settings::get('max_count') . ';';
		}
		return $rrule;
	}

	public function getTimezones() {
		$zones = ['+00:00', '+01:00', '+02:00', '+03:00', '+04:00', '+05:00', '+05:30', '+05:45', '+06:00',
			'+07:00', '+08:00', '+09:00', '+09:30', '+10:00', '+11:00', '+12:00', '-02:00',
			'-03:00', '-04:00', '-04:30', '-05:00', '-06:00', '-07:00', '-08:00', '-09:00', '-10:00',
			'-11:00', '-12:00'];
		foreach ($zones as $zone) {
			$timezones[$zone] = $this->t('timezones.' . $zone);
		}
		return $timezones;
	}

	public function getFreqOptions() {
		$freqs = ['NONE', 'HOURLY', 'DAILY', 'WEEKDAYS', 'WEEKENDS', 'WEEKLY', 'MONTHLY', 'YEARLY', 'SERIES'];
		foreach ($freqs as $freq) {
			$freqOpts[$freq] = $this->t('freq.' . $freq);
		}
		return $freqOpts;
	}

	public function getByDayOptions() {
		$bydays = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU,MO,TU,WE,TH,FR,SA', 'MO,TU,WE,TH,FR', 'SU,SA'];
		foreach ($bydays as $day) {
			$byDays[$day] = $this->t('ByDay.' . $day);
		}
		return $byDays;
	}

	public function getDayPosOptions() {
		$dayposs = ['1', '2', '3', '4', '-1', '-2'];
		foreach ($dayposs as $pos) {
			$day_pos[$pos] = $this->t('day-pos.' . $pos);
		}
		return $day_pos;
	}

	public function getMonthOptions() {
		foreach (range(1, 12) as $monthNum) {
			$months[$monthNum] = $this->t('month.' . $monthNum);
		}
		return $months;
	}

	public function getIntervalOptions() {
		$range = range(1, 360);
		return array_combine($range, $range);
	}

	public function getOccuranceOptions() {
		$range = range(1, Settings::get('max_count'));
		return array_combine($range, $range);
	}

	public function getWByDay($f) {
		$f = is_array($f) ? $f : [];
		$fields = '';
		$bydays = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];
		foreach ($bydays as $day) {
			$fields .= '
            <label class="btn btn-primary' . (in_array($day, $f) ? ' active' : '') . '">' .
			Form::checkbox('WBYDAY[' . $day . ']', $day, in_array($day, $f), ['class' => 'r_all r_weekly']) . $this->t($day) . '</label>
			';

		};
		return $fields;
	}

	/**
	 * Converts RRULE into form values needed for setting the form to the
	 * state it was in when event was created.
	 * @param  string $rrule the reccurrence rule string
	 * @return array $formVals Form values keyed by input name
	 */
	public function parseRrule($rrule) {
		if (!$rrule) {
			return [];
		}

		$rparts = explode(';', trim($rrule, ';'));
		$FREQ =
		$INTERVAL =
		$BYDAY =
		$BYSETPOS =
		$BYMONTH =
		$UNTIL =
		$COUNT = null;

		foreach ($rparts as $part) {
			list($prop, $val) = explode('=', $part);
			$$prop = $val;
		}
		switch ($FREQ) {
		case '':
		case 'NONE':
			return ['FREQ' => 'NONE'];
			break;
		case 'HOURLY':
			$formVals = ['FREQ' => 'HOURLY', 'INTERVAL' => $INTERVAL];
			break;
		case 'DAILY':
			$formVals = ['FREQ' => 'DAILY', 'INTERVAL' => $INTERVAL];
			break;
		case 'WEEKLY':
			$formVals = ['FREQ' => 'WEEKLY', 'INTERVAL' => $INTERVAL];
			if (isset($BYDAY)) {
				$BYDAYs = explode(',', $BYDAY);
				$formVals['WBYDAY'] = $BYDAYs;
			}
			break;
		case 'MONTHLY':
			$formVals = ['FREQ' => 'MONTHLY', 'INTERVAL' => $INTERVAL];
			if (isset($BYSETPOS)) {
				$formVals['MBYDAY'] = $BYDAY;
				$formVals['MBYSETPOS'] = $BYSETPOS;
				$formVals['month_on'] = 'on_the';
			} else {
				$formVals['month_on'] = 'on_day';
			}
			break;
		case 'YEARLY':
			$formVals = ['FREQ' => 'YEARLY', 'INTERVAL' => $INTERVAL];
			if (isset($BYSETPOS)) {
				$formVals['YBYDAY'] = $BYDAY;
				$formVals['YBYSETPOS'] = $BYSETPOS;
				$formVals['YBYMONTH'] = $BYMONTH;
				$formVals['year_on'] = 'on_the';
			} else {
				$formVals['year_on'] = 'on_day';
			}
			break;
		case 'SERIES':
			$formVals = ['FREQ' => 'SERIES', 'INTERVALS' => $INTERVALS];
			break;
		}

		//die(print_r($formVals));
		if (isset($UNTIL)) {
			return array_merge(['Ends' => 'DATE', 'ENDON' => $UNTIL], $formVals);
		} else {
			return array_merge(['Ends' => 'AFTER', 'COUNT' => $COUNT], $formVals);
		}

	}
}
