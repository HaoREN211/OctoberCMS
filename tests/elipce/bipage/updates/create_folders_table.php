<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateFoldersTable
 * @package Elipce\BiPage\Updates
 */
class CreateFoldersTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_folders', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_bipage_folders');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
