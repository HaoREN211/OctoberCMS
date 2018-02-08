<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateSessionsTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateSessionsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_sessions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('portal_id');
            $table->unsignedInteger('story_id');
            $table->timestamps();

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');

            $table->foreign('story_id')
                ->references('id')
                ->on('elipce_limesurvey_stories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_limesurvey_sessions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
