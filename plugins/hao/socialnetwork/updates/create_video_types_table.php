<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideoTypesTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_video_types', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 200);
            $table->string('URL', 500);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_video_types');
    }
}
