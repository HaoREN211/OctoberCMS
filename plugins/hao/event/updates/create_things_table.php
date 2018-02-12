<?php namespace Hao\Event\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateThingsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_event_things', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 100);
            $table->string('amount', 50)->nullable();
            $table->string('price', 50)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('event_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('hao_event_users');

            $table->foreign('event_id')
                ->references('id')
                ->on('hao_event_events');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_event_things');
    }
}
