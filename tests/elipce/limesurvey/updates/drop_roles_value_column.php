<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class DropRolesValueColumn
 *
 * @package Elipce\LimeSurvey\Updates
 */
class DropRolesValueColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_roles', function($table) {
            $table->dropColumn('value');
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_roles', function($table) {
            $table->string('value');
        });
    }
}
