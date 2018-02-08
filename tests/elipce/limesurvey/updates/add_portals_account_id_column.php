<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddPortalsAccountIdColumn
 * @package Elipce\LimeSurvey\Updates
 */
class AddPortalsAccountIdColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_multisite_portals', function ($table) {
            $table->unsignedInteger('limesurvey_account_id')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('elipce_multisite_portals', function ($table) {
            $table->dropColumn('limesurvey_account_id');
        });
    }

}