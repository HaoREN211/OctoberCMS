<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSurveysParticipantsReminderSentColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddSurveysParticipantsReminderSentColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_surveys_participants', function ($table) {
            $table->boolean('reminder_sent')->default(false);
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_surveys_participants', function ($table) {
            $table->dropColumn('reminder_sent');
        });
    }
}