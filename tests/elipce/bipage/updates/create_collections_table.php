<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateCollectionsTable
 * @package Elipce\BiPage\Updates
 */
class CreateCollectionsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_collections', function($table) {
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
        Schema::dropIfExists('elipce_bipage_collections');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
