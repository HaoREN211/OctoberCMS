<?php namespace Elipce\Comments\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class PagesAddCommented
 * @package Elipce\Comments\Updates
 */
class PagesAddCommented extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_pages', function ($table) {
            $table->boolean('commented')->default(true);
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_pages', function ($table) {
            $table->dropColumn('commented');
        });
    }

}
