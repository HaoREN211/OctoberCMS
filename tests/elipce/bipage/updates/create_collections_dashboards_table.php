<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateCollectionsDashboardsTable
 * @package Elipce\BiPage\Updates
 */
class CreateCollectionsDashboardsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_collections_dashboards', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('collection_id');
            $table->unsignedInteger('dashboard_id');

            $table->foreign('dashboard_id')
                ->references('id')
                ->on('elipce_bipage_dashboards')
                ->onDelete('cascade');

            $table->foreign('collection_id')
                ->references('id')
                ->on('elipce_bipage_collections')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_collections_dashboards');
    }

}
