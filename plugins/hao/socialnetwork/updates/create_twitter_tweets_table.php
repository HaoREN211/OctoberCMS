<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterTweetsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_tweets', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('id');
            $table->timestamp('created_at');
            $table->text('text')->nullable();
            $table->boolean('truncated')->nullable();
            $table->string('source', 300)->nullable();
            $table->unsignedBigInteger('in_reply_to_status_id')->nullable();
            $table->unsignedBigInteger('in_reply_to_user_id')->nullable();
            $table->unsignedBigInteger('use_id');
            $table->string('geo', 100)->nullable();
            $table->string('place', 100)->nullable();
            $table->string('contributors', 100)->nullable();
            $table->boolean('is_quote_status')->nullable();
            $table->integer('retweet_count');
            $table->integer('favorite_count');
            $table->boolean('favorited')->nullable();
            $table->boolean('retweeted')->nullable();
            $table->boolean('possibly_sensitive')->nullable();
            $table->string('lang', 20)->nullable();

            $table->primary('id');

            $table->foreign('use_id')
                ->references("id")
                ->on("hao_socialnetwork_twitter_users");

            $table->foreign('in_reply_to_user_id')
                ->references("id")
                ->on("hao_socialnetwork_twitter_users");

            $table->foreign('in_reply_to_status_id')
                ->references("id")
                ->on("hao_socialnetwork_twitter_tweets");
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_tweets');
    }
}
