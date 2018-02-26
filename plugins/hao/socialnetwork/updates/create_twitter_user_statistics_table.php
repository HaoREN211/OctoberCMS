<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterUserStatisticsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_user_statistics', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('id');
            $table->integer('followers_count')->nullable();
            $table->integer('friends_count')->nullable();
            $table->integer('listed_count')->nullable();
            $table->integer('favourites_count')->nullable();
            $table->integer('statuses_count')->nullable();
            $table->timestamp('observation_date');

            $table->foreign('id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_user_statistics');
    }
}
