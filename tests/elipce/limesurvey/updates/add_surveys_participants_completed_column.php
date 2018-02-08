<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSurveysParticipantsCompletedColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddSurveysParticipantsCompletedColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_surveys_participants', function ($table) {
            $table->boolean('completed')->default(false);
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_surveys_participants', function ($table) {
            $table->dropColumn('completed');
        });
    }
}