<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('kurtjensen_mycal_categories', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('kurtjensen_mycal_events_categories', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('events_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->primary(['events_id', 'category_id'], 'event_category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kurtjensen_mycal_events_categories');
        Schema::dropIfExists('kurtjensen_mycal_categories');
    }

}
