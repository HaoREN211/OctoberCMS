<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateDashboardsTable
 * @package Elipce\Bime\Updates
 */
class CreateDashboardsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bime_dashboards', function ($table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('id');
            $table->string('name');
            $table->string('dashboard_folder')->nullable();
            $table->string('publication_guid');
            $table->boolean('is_public')->default(false);
            $table->unsignedInteger('external_id');
            $table->unsignedInteger('account_id');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                ->references('id')
                ->on('elipce_bipage_dashboards')
                ->onDelete('cascade');

            $table->foreign('account_id')
                ->references('id')
                ->on('elipce_bime_accounts')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_bime_dashboards');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Remove generic dashboards
        DB::table('elipce_bipage_dashboards')->where('type', 'Tableau Online')->delete();
    }
}
