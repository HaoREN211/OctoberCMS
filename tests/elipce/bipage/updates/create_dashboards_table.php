<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateDashboardsTable
 * @package Elipce\BiPage\Updates
 */
class CreateDashboardsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_dashboards', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('model_class');
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_bipage_dashboards');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
