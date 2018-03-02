<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterTokensTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_tokens', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedBigInteger('twitter_id')->nullable();
            $table->string('consumer_key', 50);
            $table->string('consumer_secret', 50);
            $table->string('access_token', 100);
            $table->string('access_token_secret', 100);
            $table->string('base64_encoded_token_credentials', 200);
            $table->string('token', 200);
            $table->string('token_type',20);
            $table->string('social_network',20);
            $table->timestamps();

            $table->foreign('twitter_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_tokens');
    }
}
