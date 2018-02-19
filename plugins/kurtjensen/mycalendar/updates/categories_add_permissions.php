<?php namespace KurtJensen\MyCalendar\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CategoriesAddPermissions extends Migration
{

    public function up()
    {
        Schema::table('kurtjensen_mycal_categories', function ($table) {
            $table->integer('permission_id')->nullable()->unsigned();
        });
    }

    public function down()
    {
        Schema::table('kurtjensen_mycal_categories', function ($table) {
            $table->dropColumn('permission_id');
        });
    }

}
