<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePagesTable
 * @package Elipce\BiPage\Updates
 */
class CreatePagesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_pages', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->boolean('published')->default(false);
            $table->boolean('shared')->default(false);
            $table->text('excerpt')->nullable();
            $table->unsignedInteger('collection_id');
            $table->timestamps();

            $table->foreign('collection_id')
                ->references('id')
                ->on('elipce_bipage_collections')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_bipage_pages');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
