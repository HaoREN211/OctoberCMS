<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePreSurveysTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreatePreSurveysTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_presurveys', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('days');
            $table->unsignedInteger('story_id');
            $table->unsignedInteger('template_id');
            $table->timestamps();

            $table->foreign('story_id')
                ->references('id')
                ->on('elipce_limesurvey_stories')
                ->onDelete('cascade');

            $table->foreign('template_id')
                ->references('id')
                ->on('elipce_limesurvey_templates')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_presurveys');
    }

}
