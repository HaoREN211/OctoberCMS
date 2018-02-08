<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class DropPortalsFoldersTable
 * @package Elipce\BiPage\Updates
 */
class DropPortalsFoldersTable extends Migration
{

    public function up()
    {
        Schema::table('elipce_bipage_folders', function ($table) {
            $table->unsignedInteger('portal_id');
        });

        $rows = DB::table('elipce_bipage_portals_folders')->get();

        foreach ($rows as $row) {
            Db::table('elipce_bipage_folders')
                ->where('id', $row->folder_id)
                ->update(['portal_id' => $row->portal_id]);
        }

        Db::table('elipce_bipage_folders')
            ->where('portal_id', 0)
            ->update(['portal_id' => 1]);

        Schema::table('elipce_bipage_folders', function ($table) {
            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');
        });

        Schema::dropIfExists('elipce_bipage_portals_folders');
    }

    public function down()
    {
        Schema::create('elipce_bipage_portals_folders', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('portal_id');
            $table->unsignedInteger('folder_id');

            $table->foreign('portal_id')
                ->references('id')
                ->on('elipce_multisite_portals')
                ->onDelete('cascade');

            $table->foreign('folder_id')
                ->references('id')
                ->on('elipce_bipage_folders')
                ->onDelete('cascade');
        });

        Schema::table('elipce_bipage_folders', function ($table) {
            $table->dropForeign('elipce_bipage_folders_portal_id_foreign');
            $table->dropColumn('portal_id');
        });
    }

}
