<?php namespace KurtJensen\MyCalendar\Updates;
use DB;
use October\Rain\Database\Updates\Migration;

class UpdateEventsRelation extends Migration {

	public function up() {

		$count = DB::table('kurtjensen_mycalendar_occurrences')->count();

		if ($count) {

			$q = DB::table('kurtjensen_mycalendar_occurrences')
				->where('relation', 'events')
				->update(['relation' => 'event']);
		}
	}

	public function down() {
		DB::table('kurtjensen_mycalendar_occurrences')
			->where('relation', 'event')
			->update(['relation' => 'events']);
	}

}
