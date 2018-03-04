<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateHashtagsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_hashtags', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name', 200);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_hashtags');
    }
}
