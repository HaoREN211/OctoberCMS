<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class RenameEventCETable extends Migration {

	public function up() {
		Schema::table('kurtjensen_mycal_categorys_events', function ($table) {
			$table->renameColumn('events_id', 'event_id');
		});
	}

	public function down() {
		Schema::table('kurtjensen_mycal_categorys_events', function ($table) {
			$table->renameColumn('event_id', 'events_id');
		});
	}

}