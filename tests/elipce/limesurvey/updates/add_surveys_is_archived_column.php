<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSurveysIsArchivedColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddSurveysIsArchivedColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_surveys', function ($table) {
            $table->boolean('is_archived')->default(false);
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_surveys', function ($table) {
            $table->dropColumn('is_archived');
        });
    }
}