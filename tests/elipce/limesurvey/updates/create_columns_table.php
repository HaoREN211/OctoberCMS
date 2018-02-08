<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateColumnsTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateColumnsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_columns', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->unsignedInteger('story_id')->nullable();
            $table->unsignedInteger('session_id')->nullable();
            $table->timestamps();

            $table->foreign('story_id')
                ->references('id')
                ->on('elipce_limesurvey_stories')
                ->onDelete('cascade');

            $table->foreign('session_id')
                ->references('id')
                ->on('elipce_limesurvey_sessions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_columns');
    }

}