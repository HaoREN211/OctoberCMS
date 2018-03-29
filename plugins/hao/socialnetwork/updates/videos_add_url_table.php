<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class VideosAddUrlTable extends Migration
{
    public function up()
    {
        Schema::table('hao_socialnetwork_videos', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('source', 1000);
        });
    }
}
