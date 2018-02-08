<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateAttributesTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateAttributesTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_attributes', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('type');
            $table->unsignedInteger('participant_id');
            $table->timestamps();

            $table->foreign('participant_id')
                ->references('id')
                ->on('elipce_limesurvey_participants')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_attributes');
    }

}