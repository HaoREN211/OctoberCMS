<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterFollowersTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_followers', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('follower_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');

            $table->foreign('follower_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');

            $table->unique(['user_id', 'follower_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_followers');
    }
}
