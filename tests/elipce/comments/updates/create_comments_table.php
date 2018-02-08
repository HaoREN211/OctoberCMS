<?php namespace Elipce\Comments\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateCommentsTable
 * @package Elipce\Comments\Updates
 */
class CreateCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_comments_comments', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('pid')->nullable();
            $table->unsignedInteger('portal_id');
            $table->unsignedInteger('author_id')->index();
            $table->boolean('published')->default(true);
            $table->text('content')->nullable();
            $table->text('content_html')->nullable();
            $table->timestamps();

            $table->foreign('pid')
                ->references('id')
                ->on('elipce_comments_comments')
                ->onDelete('cascade');

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');

            $table->foreign('page_id')
                ->references('id')
                ->on('elipce_bipage_pages')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('elipce_comments_comments');
    }

}
