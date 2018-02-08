<?php namespace Elipce\Analytics\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateViewsTable
 * @package Elipce\Analytics\Updates
 */
class CreateViewsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_analytics_views', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('external_id');
            $table->unsignedInteger('property_id');
            $table->timestamps();

            $table->foreign('property_id')
                ->references('id')
                ->on('elipce_analytics_properties')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_analytics_views');
    }

}
