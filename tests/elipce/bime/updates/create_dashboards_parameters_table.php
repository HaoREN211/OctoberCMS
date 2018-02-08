<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateDashboardsParametersTable
 * @package Elipce\Bime\Updates
 */
class CreateDashboardsParametersTable extends Migration
{
    public function up()
    {
        Schema::create('elipce_bime_dashboards_parameters', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('parameter_name');
            $table->unsignedInteger('parameter_id');
            $table->unsignedInteger('dashboard_id');

            $table->foreign('parameter_id')
                ->references('id')
                ->on('elipce_bime_parameters')
                ->onDelete('cascade');

            $table->foreign('dashboard_id')
                ->references('id')
                ->on('elipce_bime_dashboards')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bime_dashboards_parameters');
    }
}
