<?php namespace Elipce\Tracker\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class PortalsAddGaid
 * @package Elipce\Tracker\Updates
 */
class PortalsAddGaid extends Migration
{

    public function up()
    {
        Schema::table('elipce_multisite_portals', function ($table) {
            $table->string('gaid')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_multisite_portals', function ($table) {
            $table->dropColumn('gaid');
        });
    }

}
