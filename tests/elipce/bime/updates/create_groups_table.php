<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateGroupsTable
 * @package Elipce\Bime\Updates
 */
class CreateGroupsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bime_groups', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('external_id');
            $table->unsignedInteger('account_id');
            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')
                ->on('elipce_bime_accounts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_bime_groups');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}
