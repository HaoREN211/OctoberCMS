<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddPreSurveysDaysColumns
 * @package Elipce\LimeSurvey\Updates
 */
class AddPreSurveysDaysColumns extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_presurveys', function ($table) {
            $table->dropColumn('days');
            $table->integer('start_days')->nullable();
            $table->integer('end_days')->nullable();
            $table->integer('duration');
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_presurveys', function ($table) {
            $table->dropColumn('start_days');
            $table->dropColumn('end_days');
            $table->dropColumn('duration');
            $table->integer('days');
        });
    }

}