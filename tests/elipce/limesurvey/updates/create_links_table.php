<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateLinksTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateLinksTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_links', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('survey_id');
            $table->unsignedInteger('linked_survey_id');
            $table->string('question');
            $table->string('answer');

            $table->foreign('survey_id')
                ->references('id')
                ->on('elipce_limesurvey_surveys')
                ->onDelete('cascade');

            $table->foreign('linked_survey_id')
                ->references('id')
                ->on('elipce_limesurvey_surveys')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_links');
    }

}
