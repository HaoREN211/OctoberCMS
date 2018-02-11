<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVocabulariesTable extends Migration
{
    public function up()
    {
        Schema::create('hao_dictionary_vocabularies', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 50);
            $table->unsignedInteger('language_id');
            $table->timestamps();

            $table->foreign('language_id')
                ->references('id')
                ->on('hao_dictionary_languages');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_dictionary_vocabularies');
    }
}
