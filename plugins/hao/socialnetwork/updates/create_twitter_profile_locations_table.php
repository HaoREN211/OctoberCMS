<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTwitterProfileLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_twitter_profile_locations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id', 30);
            $table->string('url', 200)->nullable();
            $table->string('place_type', 50)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('full_name', 200)->nullable();
            $table->string('country_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->text('contained_within')->nullable();
            $table->string('bounding_box', 200)->nullable();
            $table->text('attributes')->nullable();
            $table->string('API', 50)->default('user_show');

            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_twitter_profile_locations');
    }
}
