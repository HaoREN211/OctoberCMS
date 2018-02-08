<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateAccountsTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateAccountsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_accounts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('login');
            $table->text('password');
            $table->text('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_limesurvey_accounts');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
