<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateSingularandpluralsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_dictionary_singularandplurals', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 20);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_dictionary_singularandplurals');
    }
}
