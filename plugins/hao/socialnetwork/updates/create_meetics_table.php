<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateMeeticsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_socialnetwork_meetics', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('url', 200)->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('age')->nullable();
            $table->integer('birthday')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('photo', 200)->nullable();
            $table->string('imperfection', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('relation_type', 200)->nullable();
            $table->string('temper', 200)->nullable();
            $table->string('romantic', 200)->nullable();
            $table->string('marriage', 200)->nullable();
            $table->string('children_wish', 200)->nullable();
            $table->string('body_shape', 200)->nullable();
            $table->string('attraction', 200)->nullable();
            $table->string('living_style', 200)->nullable();
            $table->string('look', 200)->nullable();
            $table->string('ethnicity', 200)->nullable();
            $table->string('eyes', 200)->nullable();
            $table->string('hair_color', 200)->nullable();
            $table->string('hair_style', 200)->nullable();
            $table->string('marital_status', 200)->nullable();
            $table->string('smoker', 200)->nullable();
            $table->boolean('has_children')->nullable();
            $table->string('live_with', 200)->nullable();
            $table->string('job', 200)->nullable();
            $table->string('religion', 200)->nullable();
            $table->string('religion_behaviour', 200)->nullable();
            $table->string('food_habit', 200)->nullable();
            $table->string('pet', 200)->nullable();
            $table->string('language', 200)->nullable();
            $table->string('studies', 200)->nullable();
            $table->string('income', 200)->nullable();
            $table->integer('search_age_from')->nullable();
            $table->integer('search_age_to')->nullable();
            $table->string('search_body_shape', 200)->nullable();
            $table->string('search_marital_status', 200)->nullable();
            $table->string('search_children_wish', 200)->nullable();
            $table->string('search_smoker', 200)->nullable();
            $table->integer('search_height_from')->nullable();
            $table->integer('search_height_to')->nullable();
            $table->integer('search_weight_from')->nullable();
            $table->integer('search_weight_to')->nullable();
            $table->string('search_look', 200)->nullable();
            $table->string('search_has_children', 200)->nullable();
            $table->string('search_attraction', 200)->nullable();
            $table->string('search_hair_style', 200)->nullable();
            $table->string('search_hair_color', 200)->nullable();
            $table->string('search_eyes', 200)->nullable();
            $table->string('search_living_style', 200)->nullable();
            $table->string('search_nationality', 200)->nullable();
            $table->string('search_ethnicity', 200)->nullable();
            $table->string('search_live_with', 200)->nullable();
            $table->string('search_pet', 200)->nullable();
            $table->string('search_studies', 200)->nullable();
            $table->string('search_language', 200)->nullable();
            $table->string('search_job', 200)->nullable();
            $table->string('search_income', 200)->nullable();
            $table->text('search_hobbies')->nullable();
            $table->text('search_leisure')->nullable();
            $table->text('search_music')->nullable();
            $table->text('search_movie')->nullable();
            $table->text('search_sports')->nullable();
            $table->string('search_temper', 200)->nullable();
            $table->string('search_food_habit', 200)->nullable();
            $table->string('search_romantic', 200)->nullable();
            $table->string('search_marriage', 200)->nullable();
            $table->string('search_religion_behaviour', 200)->nullable();
            $table->string('search_religion', 200)->nullable();
            $table->text('music')->nullable();
            $table->text('leisure')->nullable();
            $table->text('hobbies')->nullable();
            $table->text('movie')->nullable();
            $table->text('sports')->nullable();
            $table->boolean('favorites')->default(false);
            $table->boolean('flashs')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_socialnetwork_meetics');
    }
}
