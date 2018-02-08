<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateFocusTable
 * @package Elipce\BiPage\Updates
 */
class CreateFocusTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_focus', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('portal_id');

            $table->foreign('page_id')
                ->references('id')
                ->on('elipce_bipage_pages')
                ->onDelete('cascade');

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_focus');
    }

}
