<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class RenameCategorysEventsTable extends Migration {

	public function up() {
		Schema::rename('kurtjensen_mycal_events_categories', 'kurtjensen_mycal_categorys_events');
	}

	public function down() {
		Schema::rename('kurtjensen_mycal_categorys_events', 'kurtjensen_mycal_events_categories');
	}

}