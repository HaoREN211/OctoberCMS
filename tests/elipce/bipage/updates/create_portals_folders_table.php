<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePortalsFoldersTable
 * @package Elipce\BiPage\Updates
 */
class CreatePortalsFoldersTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_portals_folders', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('portal_id');
            $table->unsignedInteger('folder_id');

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');

            $table->foreign('folder_id')
                ->references('id')
                ->on('elipce_bipage_folders')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_portals_folders');
    }

}
