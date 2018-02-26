<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;


class UniqueVocabularyTable extends Migration
{
    public function up()
    {
        Schema::table('hao_dictionary_vocabularies', function ($table) {
            $table->engine = 'InnoDB';
            $table->unique(['name', 'language_id']);
        });
    }

    public function down(){

    }
}
