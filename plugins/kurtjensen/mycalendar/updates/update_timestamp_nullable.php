<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use DbDongle;

class UpdateTimestampsNullable extends Migration
{
    public function up()
    {
        DbDongle::disableStrictMode();

        DbDongle::convertTimestamps('kurtjensen_mycal_categories');
        DbDongle::convertTimestamps('kurtjensen_mycal_events');
        DbDongle::convertTimestamps('kurtjensen_mycalendar_occurrences');
    }

    public function down()
    {
        // ...
    }
}
