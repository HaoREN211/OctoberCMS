<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddRolesDescriptionColumn
 *
 * @package Elipce\LimeSurvey\Updates
 */
class AddRolesDescriptionColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_roles', function($table) {
            $table->string('description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_roles', function($table) {
            $table->dropColumn('description');
        });
    }
}