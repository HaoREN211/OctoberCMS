<?php namespace Hao\Job\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLiepinOffersTable extends Migration
{
    public function up()
    {
        Schema::create('hao_job_liepin_offers', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('title', 200)->nullable();
            $table->unsignedBigInteger('enterprise')->nullable();
            $table->unsignedInteger('department')->nullable();
            $table->unsignedInteger('filiere')->nullable();
            $table->unsignedInteger('responsable')->nullable();
            $table->unsignedInteger('man')->nullable();
            $table->unsignedInteger('salary')->nullable();
            $table->unsignedInteger('location')->nullable();
            $table->unsignedInteger('response')->nullable();
            $table->timestamps();

            $table->foreign('enterprise')
                ->references('id')
                ->on('hao_job_liepin_enterprises');

            $table->foreign('department')
                ->references('id')
                ->on('hao_job_liepin_departments');

            $table->foreign('filiere')
                ->references('id')
                ->on('hao_job_liepin_filieres');

            $table->foreign('responsable')
                ->references('id')
                ->on('hao_job_liepin_responsables');

            $table->foreign('man')
                ->references('id')
                ->on('hao_job_liepin_men');

            $table->foreign('salary')
                ->references('id')
                ->on('hao_job_liepin_salaries');

            $table->foreign('location')
                ->references('id')
                ->on('hao_job_liepin_locations');

            $table->foreign('response')
                ->references('id')
                ->on('hao_job_liepin_responses');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_job_liepin_offers');
    }
}
