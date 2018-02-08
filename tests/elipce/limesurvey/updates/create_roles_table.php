<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateRolesTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateRolesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_roles', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('value');
            $table->unsignedInteger('story_id');
            $table->timestamps();

            $table->foreign('story_id')
                ->references('id')
                ->on('elipce_limesurvey_stories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_limesurvey_roles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
