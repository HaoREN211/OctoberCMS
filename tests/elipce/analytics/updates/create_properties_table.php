<?php namespace Elipce\Analytics\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePropertiesTable
 * @package Elipce\Analytics\Updates
 */
class CreatePropertiesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_analytics_properties', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('external_id');
            $table->string('ga_account_id');
            $table->unsignedInteger('account_id');
            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')
                ->on('elipce_analytics_accounts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_analytics_properties');
    }

}
