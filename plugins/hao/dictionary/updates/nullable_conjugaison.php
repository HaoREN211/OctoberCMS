<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;


class NullableConjugaisonTable extends Migration
{
    public function up()
    {
        Schema::table('hao_dictionary_translations', function ($table) {
            $table->engine = 'InnoDB';
            $table->string('name', 50)->nullable()->change();
        });
    }

    public function down(){

    }
}
