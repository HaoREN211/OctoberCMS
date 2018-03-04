<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterMediaTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_media', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('media_url', 200)->nullable();
            $table->string('media_url_https', 200)->nullable();
            $table->string('url', 200)->nullable();
            $table->string('display_url', 200)->nullable();
            $table->string('expanded_url', 200)->nullable();
            $table->string('type', 50)->nullable();
            $table->unsignedBigInteger('source_status_id')->nullable();
            $table->unsignedBigInteger('source_user_id')->nullable();

            $table->foreign('source_user_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');

            $table->foreign('source_status_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_tweets');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_media');
    }
}
