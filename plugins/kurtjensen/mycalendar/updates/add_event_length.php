<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddEventLength extends Migration {

	public function up() {
		Schema::table('kurtjensen_mycal_events', function ($table) {
			$table->time('length')->nullable()->after('date');
			$table->string('pattern')->nullable()->after('is_published');
		});
	}

	public function down() {
		Schema::table('kurtjensen_mycal_events', function ($table) {
			$table->dropColumn('length');
		});
	}

}
