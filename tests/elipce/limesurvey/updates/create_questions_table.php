<?php namespace Elipce\LimeSurvey\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateQuestionsTable
 * @package Elipce\LimeSurvey\Updates
 */
class CreateQuestionsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_limesurvey_questions', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('template_id');
            $table->unsignedInteger('question_id')->nullable();
            $table->timestamps();

            $table->foreign('template_id')
                ->references('id')
                ->on('elipce_limesurvey_templates')
                ->onDelete('cascade');

            $table->foreign('question_id')
                ->references('id')
                ->on('elipce_limesurvey_questions')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_limesurvey_questions');
    }

}