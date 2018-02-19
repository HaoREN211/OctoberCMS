<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddEventLinkTime extends Migration {

	public function up() {
		Schema::table('kurtjensen_mycal_events', function ($table) {
			$table->time('time')->nullable()->after('day');
			$table->string('link')->nullable()->after('text');
		});
	}

	public function down() {
		Schema::table('kurtjensen_mycal_events', function ($table) {
			$table->dropColumn('time');
			$table->dropColumn('link');
		});
	}

}
