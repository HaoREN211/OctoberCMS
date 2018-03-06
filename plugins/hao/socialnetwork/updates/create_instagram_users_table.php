<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInstagramUsersTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_instagram_users', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 32);
            $table->string('full_name', 200)->nullable();
            $table->string('username', 200)->nullable();
            $table->string('biography', 500)->nullable();
            $table->boolean('blocked_by_viewer')->nullable();
            $table->boolean('country_block')->nullable();
            $table->string('external_url', 200)->nullable();
            $table->string('external_url_linkshimmed', 500)->nullable();
            $table->integer('followed_by')->nullable();
            $table->boolean('followed_by_viewer')->nullable();
            $table->integer('follows')->nullable();
            $table->boolean('follows_viewer')->nullable();
            $table->string('profile_pic_url', 500)->nullable();
            $table->string('profile_pic_url_hd', 500)->nullable();
            $table->boolean('requested_by_viewer')->nullable();
            $table->boolean('has_blocked_viewer')->nullable();
            $table->boolean('has_requested_viewer')->nullable();
            $table->boolean('is_private')->nullable();
            $table->boolean('is_verified')->nullable();
            $table->string('connected_fb_page', 100)->nullable();
            $table->timestamps();

            $table->primary('id', 'pk_instagram_user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_instagram_users');
    }
}
