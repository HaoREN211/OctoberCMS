<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateParticipantsTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateParticipantsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_participants', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('uid');
            $table->string('email');
            $table->string('fn');
            $table->string('sn');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('session_id');
            $table->timestamps();

            $table->foreign('session_id')
                ->references('id')
                ->on('elipce_limesurvey_sessions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_participants');
    }

}
