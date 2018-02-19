<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateOccurrencesTable extends Migration
{

    public function up()
    {
        Schema::create('kurtjensen_mycalendar_occurrences', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('event_id')->unsigned()->nullable()->index();
            $table->string('relation')->nullable();
            $table->integer('relation_id')->unsigned()->nullable()->index();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_modified')->nullable()->default(false);
            $table->boolean('is_allday')->nullable()->default(false);
            $table->boolean('is_canceled')->nullable()->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kurtjensen_mycalendar_occurrences');
    }

}
