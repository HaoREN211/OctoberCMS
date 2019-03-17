<?php namespace Hao\Cv\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFormationsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_cv_formations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('cv_id');
            $table->date('start_date');
            $table->date('end_time');
            $table->string("school", 200);
            $table->string("background", 200);
            $table->string("domain", 200);
            $table->timestamps();

            $table->foreign('cv_id')
                ->references('id')
                ->on('hao_cv_cvs');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_cv_formations');
    }
}
