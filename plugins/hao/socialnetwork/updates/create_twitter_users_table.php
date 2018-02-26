<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterUsersTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_users', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('id');
            $table->string('name', 50);
            $table->string("screen_name", 50);
            $table->string('location', 100)->nullable();
            $table->string('profile_location_id',30)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 200)->nullable();
            $table->boolean('protected')->nullable();
            $table->integer('followers_count')->nullable();
            $table->integer('friends_count')->nullable();
            $table->integer('listed_count')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('favourites_count')->nullable();
            $table->integer('utc_offset')->nullable();
            $table->string('time_zone', 50)->nullable();
            $table->boolean('geo_enabled')->nullable();
            $table->boolean('verified')->nullable();
            $table->integer('statuses_count')->nullable();
            $table->string('lang', 10)->nullable();
            $table->boolean('contributors_enabled')->nullable();
            $table->boolean('is_translator')->nullable();
            $table->boolean('is_translation_enabled')->nullable();
            $table->string('profile_background_color', 10)->nullable();
            $table->string('profile_background_image_url',200)->nullable();
            $table->string('profile_background_image_url_https', 200)->nullable();
            $table->boolean('profile_background_tile')->nullable();
            $table->string('profile_image_url', 200)->nullable();
            $table->string('profile_image_url_https', 200)->nullable();
            $table->string('profile_link_color', 10)->nullable();
            $table->string('profile_sidebar_border_color', 10)->nullable();
            $table->string('profile_sidebar_fill_color', 10)->nullable();
            $table->string('profile_text_color', 10)->nullable();
            $table->boolean('profile_use_background_image')->nullable();
            $table->boolean('has_extended_profile')->nullable();
            $table->boolean('default_profile')->nullable();
            $table->boolean('default_profile_image')->nullable();
            $table->string('following', 50)->nullable();
            $table->string('follow_request_sent', 50)->nullable();
            $table->string('notifications', 50)->nullable();
            $table->string('translator_type', 200)->nullable();
            $table->string('API', 50)->default('user_show');

            $table->primary('id');

            $table->foreign('profile_location_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_profile_locations');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_users');
    }
}
