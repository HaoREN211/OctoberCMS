<?php namespace Hao\Event\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('hao_event_users', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 20);
            $table->string('phone', '20');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_event_users');
    }
}
