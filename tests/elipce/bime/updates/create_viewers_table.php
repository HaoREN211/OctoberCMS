<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateViewersTable
 * @package Elipce\Bime\Updates
 */
class CreateViewersTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bime_viewers', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('login');
            $table->unsignedInteger('external_id');
            $table->char('access_token', 16)->nullable();
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('account_id');
            $table->timestamps();

            $table->foreign('group_id')
                ->references('id')
                ->on('elipce_bime_groups')
                ->onDelete('cascade');

            $table->foreign('account_id')
                ->references('id')
                ->on('elipce_bime_accounts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bime_viewers');
    }

}
