<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class DropGroupFilteredColumn
 * @package Elipce\Bime\Updates
 */
class DropGroupFilteredColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_collections', function ($table) {
            $table->dropColumn('is_group_filtered');
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_collections', function ($table) {
            $table->boolean('is_group_filtered')->default(false);
        });
    }

}
