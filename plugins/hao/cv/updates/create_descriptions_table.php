<?php namespace Hao\Cv\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_cv_descriptions', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('enterprise_id');
            $table->string("description", 200);
            $table->integer("order");
            $table->timestamps();

            $table->foreign('enterprise_id')
                ->references('id')
                ->on('hao_cv_experiences');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_cv_descriptions');
    }
}
