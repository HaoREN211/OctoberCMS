<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddPagesDashboardsPositionColumn
 * @package Elipce\BiPage\Updates
 */
class AddPagesDashboardsPositionColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_pages_dashboards', function ($table) {
            $table->integer('position')->default(0);
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_pages_dashboards', function ($table) {
            $table->dropColumn('position');
        });
    }

}
