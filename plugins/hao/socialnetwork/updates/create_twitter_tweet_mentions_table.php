<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterTweetMentionsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_tweet_mentions', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tweet_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('tweet_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_tweets');

            $table->foreign('user_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_tweet_mentions');
    }
}
