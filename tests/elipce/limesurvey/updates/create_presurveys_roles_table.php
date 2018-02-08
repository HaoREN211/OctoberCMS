<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreatePreSurveysRolesTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreatePreSurveysRolesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_presurveys_roles', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('presurvey_id');
            $table->unsignedInteger('role_id');

            $table->foreign('presurvey_id')
                ->references('id')
                ->on('elipce_limesurvey_presurveys')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('elipce_limesurvey_roles')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_presurveys_roles');
    }

}
