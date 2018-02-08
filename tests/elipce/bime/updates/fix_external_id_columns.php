<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class FixExternalIdColumns
 * @package Elipce\Bime\Updates
 */
class FixExternalIdColumns extends Migration
{

    public function up()
    {
        Schema::table('elipce_bime_groups', function ($table) {
            $table->bigInteger('external_id')->unsigned()->change();
        });
        Schema::table('elipce_bime_viewers', function ($table) {
            $table->bigInteger('external_id')->unsigned()->change();
        });
        Schema::table('elipce_bime_dashboards', function ($table) {
            $table->bigInteger('external_id')->unsigned()->change();
        });
    }

    public function down()
    {
        Schema::table('elipce_bime_groups', function ($table) {
            $table->unsignedInteger('external_id')->change();
        });
        Schema::table('elipce_bime_viewers', function ($table) {
            $table->unsignedInteger('external_id')->change();
        });
        Schema::table('elipce_bime_dashboards', function ($table) {
            $table->unsignedInteger('external_id')->change();
        });
    }
}