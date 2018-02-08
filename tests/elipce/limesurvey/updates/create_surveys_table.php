<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateSurveysTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateSurveysTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_surveys', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('active')->default(false);
            $table->unsignedInteger('external_id');
            $table->unsignedInteger('session_id');
            $table->unsignedInteger('role_id');
            $table->timestamps();

            $table->foreign('role_id')
                ->references('id')
                ->on('elipce_limesurvey_roles');

            $table->foreign('session_id')
                ->references('id')
                ->on('elipce_limesurvey_sessions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_surveys');
    }

}
