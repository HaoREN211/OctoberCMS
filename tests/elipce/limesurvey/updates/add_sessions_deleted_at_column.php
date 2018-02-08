<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSessionsDeletedAtColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddSessionsDeletedAtColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_sessions', function ($table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_sessions', function ($table) {
            $table->dropColumn('deleted_at');
        });
    }
}