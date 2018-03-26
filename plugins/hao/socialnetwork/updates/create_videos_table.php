<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_videos', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('url', 1000);
            $table->unsignedInteger('type');
            $table->string('name', 1000);
            $table->boolean('is_liked');
            $table->boolean('is_watched');
            $table->timestamps();

            $table->foreign('type')
                ->references('id')
                ->on('hao_socialnetwork_video_types');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_videos');
    }
}
