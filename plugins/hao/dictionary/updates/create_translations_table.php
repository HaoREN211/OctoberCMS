<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_dictionary_translations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 50);
            $table->string('description', 20);
            $table->unsignedInteger('vocabulary_id');
            $table->unsignedInteger('grammaticalgender_id');
            $table->unsignedInteger('partofspeeche_id');
            $table->unsignedInteger('singularandplural_id');
            $table->timestamps();

            $table->foreign('vocabulary_id')
                ->references('id')
                ->on('hao_dictionary_vocabularies');

            $table->foreign('grammaticalgender_id')
                ->references('id')
                ->on('hao_dictionary_grammaticalgenders');

            $table->foreign('partofspeeche_id')
                ->references('id')
                ->on('hao_dictionary_partofspeeches');

            $table->foreign('singularandplural_id')
                ->references('id')
                ->on('hao_dictionary_singularandplurals');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_dictionary_translations');
    }
}
