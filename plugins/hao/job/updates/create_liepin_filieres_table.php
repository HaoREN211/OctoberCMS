<?php namespace Hao\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLiepinFilieresTable extends Migration
{
    public function up()
    {
        Schema::create('hao_job_liepin_filieres', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 200)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_job_liepin_filieres');
    }
}
