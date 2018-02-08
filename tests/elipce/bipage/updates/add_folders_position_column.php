<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddFoldersPositionColumn
 * @package Elipce\BiPage\Updates
 */
class AddFoldersPositionColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_folders', function ($table) {
           $table->integer('position')->default(0);
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_folders', function ($table) {
            $table->dropColumn('position');
        });
    }

}
