<?php namespace Elipce\Tableau\Updates;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTables
 * @package Elipce\Tableau\Updates
 */
class CreateTables extends Migration
{
    public function up()
    {
        Schema::create('elipce_tableau_sites', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('login');
            $table->text('password');
            $table->text('token');
            $table->string('external_id');
            $table->string('owner_id');
            $table->timestamps();
        });

        Schema::create('elipce_tableau_workbooks', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('contentUrl');
            $table->boolean('showTabs');
            $table->string('external_id');
            $table->unsignedInteger('site_id');
            $table->timestamps();

            $table->foreign('site_id')
                ->references('id')
                ->on('elipce_tableau_sites')
                ->onDelete('cascade');
        });

        Schema::create('elipce_tableau_views', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('contentUrl');
            $table->string('external_id');
            $table->unsignedInteger('workbook_id');
            $table->unsignedInteger('site_id');
            $table->timestamps();

            $table->foreign('site_id')
                ->references('id')
                ->on('elipce_tableau_sites')
                ->onDelete('cascade');

            $table->foreign('workbook_id')
                ->references('id')
                ->on('elipce_tableau_workbooks')
                ->onDelete('cascade');
        });

        Schema::create('elipce_tableau_viewers', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('external_id');
            $table->unsignedInteger('site_id');
            $table->timestamps();

            $table->foreign('site_id')
                ->references('id')
                ->on('elipce_tableau_sites')
                ->onDelete('cascade');
        });

        Schema::create('elipce_tableau_groups', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('external_id');
            $table->unsignedInteger('site_id');
            $table->timestamps();

            $table->foreign('site_id')
                ->references('id')
                ->on('elipce_tableau_sites')
                ->onDelete('cascade');
        });

        Schema::create('elipce_tableau_subscriptions', function ($table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('workbook_id');

            $table->primary(['group_id', 'workbook_id']);

            $table->foreign('group_id')
                ->references('id')
                ->on('elipce_tableau_groups')
                ->onDelete('cascade');

            $table->foreign('workbook_id')
                ->references('id')
                ->on('elipce_tableau_workbooks')
                ->onDelete('cascade');
        });

        Schema::create('elipce_tableau_viewers_groups', function ($table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('viewer_id');
            $table->unsignedInteger('group_id');

            $table->primary(['viewer_id', 'group_id']);

            $table->foreign('viewer_id')
                ->references('id')
                ->on('elipce_tableau_viewers')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')
                ->on('elipce_tableau_groups')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('elipce_tableau_viewers');
        Schema::dropIfExists('elipce_tableau_subscriptions');
        Schema::dropIfExists('elipce_tableau_viewers_groups');
        Schema::dropIfExists('elipce_tableau_groups');
        Schema::dropIfExists('elipce_tableau_workbooks');
        Schema::dropIfExists('elipce_tableau_views');
        Schema::dropIfExists('elipce_tableau_sites');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Remove generic dashboards
        DB::table('elipce_bipage_dashboards')->where('type', 'Tableau Online')->delete();
    }
}
