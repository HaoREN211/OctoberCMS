<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddCollectionsGroupsColumn
 * @package Elipce\Bime\Updates
 */
class AddCollectionsGroupsColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_collections', function ($table) {
            $table->text('bime_groups')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_collections', function ($table) {
            $table->dropColumn('bime_groups');
        });
    }

}
