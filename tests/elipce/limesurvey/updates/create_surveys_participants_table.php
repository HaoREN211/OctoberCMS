<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateSurveysParticipantsTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateSurveysParticipantsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_surveys_participants', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('token')->nullable();
            $table->unsignedInteger('survey_id');
            $table->unsignedInteger('participant_id');

            $table->foreign('survey_id')
                ->references('id')
                ->on('elipce_limesurvey_surveys')
                ->onDelete('cascade');

            $table->foreign('participant_id')
                ->references('id')
                ->on('elipce_limesurvey_participants')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_surveys_participants');
    }

}
