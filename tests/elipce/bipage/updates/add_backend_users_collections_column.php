<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class BackendUsersAddCollections
 * @package Elipce\BiPage\Updates
 */
class AddBackendUsersCollectionsColumn extends Migration
{

    public function up()
    {
        Schema::table('backend_users', function ($table) {
            $table->text('collections')->nullable();
        });
    }

    public function down()
    {
        Schema::table('backend_users', function ($table) {
            $table->dropColumn('collections');
        });
    }

}
