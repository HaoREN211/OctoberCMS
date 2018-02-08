<?php namespace Elipce\Bime\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateSubscriptionsTable
 * @package Elipce\Bime\Updates
 */
class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('elipce_bime_subscriptions', function ($table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('dashboard_id');

            $table->primary(['group_id', 'dashboard_id']);

            $table->foreign('group_id')
                ->references('id')
                ->on('elipce_bime_groups')
                ->onDelete('cascade');

            $table->foreign('dashboard_id')
                ->references('id')
                ->on('elipce_bime_dashboards')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bime_subscriptions');
    }

}
