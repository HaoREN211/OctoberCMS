<?php namespace Elipce\News\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateNewsTable
 * @package Elipce\News\Updates
 */
class CreateNewsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_news_news', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('text');
            $table->integer('page_id')->nullable();
            $table->unsignedInteger('portal_id');
            $table->timestamps();

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_news_news');
    }

}
