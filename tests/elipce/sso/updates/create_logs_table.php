<?php namespace Elipce\SSO\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateLogsTable
 * @package Elipce\News\Updates
 */
class CreateLogsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_sso_logs', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('ip');
            $table->string('email')->nullable();
            $table->string('author');
            $table->string('result');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_sso_logs');
    }

}
