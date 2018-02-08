<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class BackendUsersAddBimeGroups
 * @package Elipce\Bime\Updates
 */
class BackendUsersAddBimeGroups extends Migration
{

    public function up()
    {
        Schema::table('backend_users', function ($table) {
            $table->text('bime_groups')->nullable();
        });
    }

    public function down()
    {
        Schema::table('backend_users', function ($table) {
            $table->dropColumn('bime_groups');
        });
    }

}
