<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSurveysTemplateIdColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddSurveysTemplateIdColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_surveys', function ($table) {
            $table->unsignedInteger('template_id');
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_surveys', function ($table) {
            $table->dropColumn('template_id');
        });
    }

}