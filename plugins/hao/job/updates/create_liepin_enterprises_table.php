<?php namespace Hao\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLiepinEnterprisesTable extends Migration
{
    public function up()
    {
        Schema::create('hao_job_liepin_enterprises', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('URL',50)->nullable();
            $table->string('name', 30);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_job_liepin_enterprises');
    }
}
