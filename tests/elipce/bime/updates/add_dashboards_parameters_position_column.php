<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddDashboardsParametersPositionColumn
 * @package Elipce\Bime\Updates
 */
class AddDashboardsParametersPositionColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bime_dashboards_parameters', function ($table) {
            $table->integer('position')->default(0);
        });
    }

    public function down()
    {
        Schema::table('elipce_bime_dashboards_parameters', function ($table) {
            $table->dropColumn('position');
        });
    }

}
