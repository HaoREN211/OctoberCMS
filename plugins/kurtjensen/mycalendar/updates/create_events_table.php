<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateEventsTable extends Migration
{

    public function up()
    {
        Schema::create('kurtjensen_mycal_events', function ($table) {
            // 'link' => 'data-toggle="modal" href="#content-confirmation"', 'txt' => 'test-again', 'class' => 'text-success'
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('name')->nullable();
            $table->integer('year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->text('text')->nullable();
            $table->boolean('is_published')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kurtjensen_mycal_events');
    }

}
