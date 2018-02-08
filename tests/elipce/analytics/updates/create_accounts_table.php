<?php namespace Elipce\Analytics\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateAccountsTable
 * @package Elipce\Analytics\Updates
 */
class CreateAccountsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_analytics_accounts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_analytics_accounts');
    }

}
