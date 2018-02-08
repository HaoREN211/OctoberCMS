<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateAccountsTable
 * @package Elipce\Bime\Updates
 */
class CreateAccountsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bime_accounts', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('url');
            $table->text('token');
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_bime_accounts');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
