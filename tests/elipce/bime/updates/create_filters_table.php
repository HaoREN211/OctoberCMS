<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateFiltersTable
 * @package Elipce\Bime\Updates
 */
class CreateFiltersTable extends Migration
{
    public function up()
    {
        Schema::create('elipce_bime_filters', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('value');
            $table->integer('priority');
            $table->unsignedInteger('parameter_id');
            $table->unsignedInteger('collection_id');
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('page_id')->nullable();
            $table->unsignedInteger('viewer_id')->nullable();
            $table->timestamps();

            $table->foreign('parameter_id')
                ->references('id')
                ->on('elipce_bime_parameters')
                ->onDelete('cascade');

            $table->foreign('collection_id')
                ->references('id')
                ->on('elipce_bipage_collections')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bime_filters');
    }
}
