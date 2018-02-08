<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateFoldersPagesTable
 * @package Elipce\BiPage\Updates
 */
class CreateFoldersPagesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_folders_pages', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('folder_id');

            $table->foreign('page_id')
                ->references('id')
                ->on('elipce_bipage_pages')
                ->onDelete('cascade');

            $table->foreign('folder_id')
                ->references('id')
                ->on('elipce_bipage_folders')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_folders_pages');
    }

}
