<?php namespace Hao\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLiepinSalariesTable extends Migration
{
    public function up()
    {
        Schema::create('hao_job_liepin_salaries', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 200)->nullable();
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_job_liepin_salaries');
    }
}
