<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddColumnsFieldColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddColumnsFieldColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_limesurvey_columns', function ($table) {
            $table->string('field')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_limesurvey_columns', function ($table) {
            $table->dropColumn('field');
        });
    }
}