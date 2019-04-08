<?php namespace Hao\Journal\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateJournalsTable extends Migration
{
    public function up()
    {
        Schema::create('hao_journal_journals', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->date("date_journal");
            $table->text("content");
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('backend_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hao_journal_journals');
    }
}
