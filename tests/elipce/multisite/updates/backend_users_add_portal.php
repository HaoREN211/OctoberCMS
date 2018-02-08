<?php namespace Elipce\Multisite\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class BackendUsersAddPortal
 * @package Elipce\Multisite\Updates
 */
class BackendUsersAddPortal extends Migration
{

    public function up()
    {
        Schema::table('backend_users', function ($table) {
            $table->integer('portal_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('backend_users', function ($table) {
            $table->dropColumn('portal_id');
        });
    }

}