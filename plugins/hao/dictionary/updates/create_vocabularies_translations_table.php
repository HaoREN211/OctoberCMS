<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVocabulariesTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_dictionary_vocabularies_translations', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('vocabulary_id');
            $table->unsignedInteger('translation_id');

            $table->foreign('vocabulary_id')
                ->references('id')
                ->on('hao_dictionary_vocabularies');

            $table->foreign('translation_id')
                ->references('id')
                ->on('hao_dictionary_translations');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_dictionary_vocabularies');
    }
}
