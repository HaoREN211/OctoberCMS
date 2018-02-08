<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateStoriesTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateStoriesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_stories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
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
        Schema::dropIfExists('elipce_limesurvey_stories');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
