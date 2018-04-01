<?php namespace Hao\Photo\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePhotosTable extends Migration
{
    public function up()
    {
        Schema::create('hao_photo_photos', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('UUID', 50);
            $table->string('url', 200);
            $table->string('path', 100);
            $table->boolean('is_watched')->default(false);
            $table->boolean('is_liked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_photo_photos');
    }
}
