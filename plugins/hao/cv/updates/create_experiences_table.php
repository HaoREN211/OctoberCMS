<?php namespace Hao\Cv\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateExperiencesTable extends Migration
{
    public function up()
    {
        Schema::create('hao_cv_experiences', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('cv_id');
            $table->date('start_date');
            $table->date('end_time');
            $table->string("enterprise", 200);
            $table->string("position", 200);
            $table->timestamps();

            $table->foreign('cv_id')
                ->references('id')
                ->on('hao_cv_cvs');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_cv_experiences');
    }
}
