<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class RenameFromColumn
 * @package Elipce\BiPage\Updates
 */
class RenameFromColumn extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_pages_dashboards', function($table)
        {
            $table->renameColumn('from', 'source');
        });
    }

    public function down()
    {
        Schema::table('elipce_bipage_pages_dashboards', function($table)
        {
            $table->renameColumn('source', 'from');
        });
    }

}
