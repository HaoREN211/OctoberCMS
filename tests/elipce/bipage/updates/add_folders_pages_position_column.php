<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddFoldersPagesPositionColumn
 * @package Elipce\BiPage\Updates
 */
class AddFoldersPagesPositionColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_folders_pages', function ($table) {
            $table->integer('position')->default(0);
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_folders_pages', function ($table) {
            $table->dropColumn('position');
        });
    }

}
