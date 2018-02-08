<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSurveysDaysColumns
 * @package Elipce\LimeSurvey\Updates
 */
class AddSurveysDaysColumns extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_surveys', function($table)
        {
            $table->integer('duration');
            $table->integer('start_days')->nullable();
            $table->integer('end_days')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_surveys', function($table)
        {
            $table->dropColumn('duration');
            $table->dropColumn('start_days');
            $table->dropColumn('end_days');
        });
    }
}