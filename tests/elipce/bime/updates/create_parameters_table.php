<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateParametersTable
 * @package Elipce\Bime\Updates
 */
class CreateParametersTable extends Migration
{
    public function up()
    {
        Schema::create('elipce_bime_parameters', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bime_parameters');
    }
}
