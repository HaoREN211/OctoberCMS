<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddSessionsColumns
 * @package Elipce\LimeSurvey\Updates
 */
class AddSessionsColumns extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_sessions', function ($table) {
            $table->string('uid_column');
            $table->string('email_column');
            $table->string('fn_column');
            $table->string('sn_column');
            $table->string('role_column');
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_sessions', function ($table) {
            $table->dropColumn('uid_column');
            $table->dropColumn('email_column');
            $table->dropColumn('fn_column');
            $table->dropColumn('sn_column');
            $table->dropColumn('role_column');
        });
    }

}