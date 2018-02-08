<?php namespace Elipce\Bookmark\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateBookmarksTable
 * @package Elipce\Bookmark\Updates
 */
class CreateBookmarksTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_bookmarks', function ($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('portal_id');

            $table->foreign('page_id')
                ->references('id')
                ->on('elipce_bipage_pages')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_bookmarks');
    }

}
