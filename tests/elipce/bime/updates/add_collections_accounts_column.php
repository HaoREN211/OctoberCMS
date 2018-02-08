<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class AddCollectionsAccountsColumn
 * @package Elipce\Bime\Updates
 */
class AddCollectionsAccountsColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_collections', function ($table) {
            $table->text('bime_accounts')->nullable();
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_collections', function ($table) {
            $table->dropColumn('bime_accounts');
        });
    }

}
