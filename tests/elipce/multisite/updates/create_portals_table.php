<?php namespace Elipce\Multisite\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePortalsTable
 * @package Elipce\Multisite\Updates
 */
class CreatePortalsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_multisite_portals', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('domain');
            $table->string('subdomain')->nullable();
            $table->string('host');
            $table->string('theme');
            $table->timestamps();

            $table->unique(['domain', 'subdomain']);
            $table->unique('host');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_multisite_portals');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}