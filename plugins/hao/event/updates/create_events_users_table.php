<?php namespace Hao\Event\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateEventsUsersTable extends Migration
{
    public function up()
    {
        Schema::create('hao_event_events_users', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('event_id');

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
        Schema::dropIfExists('hao_event_events');
    }
}
