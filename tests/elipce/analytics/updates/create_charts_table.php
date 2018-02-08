<?php namespace Elipce\Analytics\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateChartsTable
 * @package Elipce\Analytics\Updates
 */
class CreateChartsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_analytics_charts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('id');
            $table->string('name');
            $table->string('metric');
            $table->string('dimension');
            $table->string('type');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('view_id');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                ->references('id')
                ->on('elipce_bipage_dashboards')
                ->onDelete('cascade');

            $table->foreign('view_id')
                ->references('id')
                ->on('elipce_analytics_views')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::table('elipce_bipage_dashboards')->where('type', 'Google Analytics')->delete();
        Schema::dropIfExists('elipce_analytics_charts');
    }

}
