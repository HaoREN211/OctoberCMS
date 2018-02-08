<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePagesDashboardsTable
 * @package Elipce\BiPage\Updates
 */
class CreatePagesDashboardsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_pages_dashboards', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('dashboard_id');
            $table->string('from')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('help')->nullable();

            $table->foreign('page_id')
                ->references('id')
                ->on('elipce_bipage_pages')
                ->onDelete('cascade');

            $table->foreign('dashboard_id')
                ->references('id')
                ->on('elipce_bipage_dashboards')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_pages_dashboards');
    }

}
