<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterTweetHashtagsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_tweet_hashtags', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('tweet_id');
            $table->unsignedBigInteger('hashtag_id');

            $table->primary(['tweet_id', 'hashtag_id'], 'tweetHashtag_pk');

            $table->foreign('tweet_id', 'tweetHashtag_tweet')
                ->references('id')
                ->on('hao_socialnetwork_twitter_tweets');

            $table->foreign('hashtag_id', 'tweetHashtag_hashtag')
                ->references('id')
                ->on('hao_socialnetwork_hashtags');

            $table->unique(['tweet_id', 'hashtag_id'], 'unique_tweet_user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_tweet_hashtags');
    }
}
