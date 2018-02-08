<?php namespace Elipce\Multisite\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePortalsTable
 * @package Elipce\Multisite\Updates
 */
class UsersAddPortal extends Migration
{

    public function up()
    {
        Schema::table('users', function ($table) {
            $table->integer('portal_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('portal_id');
        });
    }

}