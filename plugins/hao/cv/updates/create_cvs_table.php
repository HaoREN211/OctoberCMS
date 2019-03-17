<?php namespace Hao\Cv\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCvsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_cv_cvs', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->string("name", 255);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('backend_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_cv_cvs');
    }
}
