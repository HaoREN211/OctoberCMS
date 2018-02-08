<?php namespace Elipce\Analytics\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateMetadataTable
 * @package Elipce\Analytics\Updates
 */
class CreateMetadataTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_analytics_metadata', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->text('description');
            $table->string('code');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_analytics_metadata');
    }

}
