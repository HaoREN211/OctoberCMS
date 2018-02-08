<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateStoriesRolesTable
 *
 * @package Elipce\LimeSurvey\Updates
 */
class CreateStoriesRolesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_stories_roles', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('code');
            $table->unsignedInteger('story_id');
            $table->unsignedInteger('role_id');

            $table->foreign('story_id')
                ->references('id')
                ->on('elipce_limesurvey_stories')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('elipce_limesurvey_roles')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_stories_roles');
    }
}
