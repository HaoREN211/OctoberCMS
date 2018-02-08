<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSessionsCreatedByColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddSessionsCreatedByColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_sessions', function ($table) {
            $table->unsignedInteger('created_by')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_sessions', function ($table) {
            $table->dropColumn('created_by');
        });
    }

}