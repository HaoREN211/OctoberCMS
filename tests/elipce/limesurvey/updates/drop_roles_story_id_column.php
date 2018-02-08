<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class DropRolesStoryIdColumn
 *
 * @package Elipce\LimeSurvey\Updates
 */
class DropRolesStoryIdColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_roles', function($table) {
            $table->dropForeign('elipce_limesurvey_roles_story_id_foreign');
            $table->dropColumn('story_id');
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_roles', function($table) {
            $table->unsignedInteger('story_id');

            $table->foreign('story_id')
                ->references('id')
                ->on('elipce_limesurvey_stories')
                ->onDelete('cascade');
        });
    }
}
