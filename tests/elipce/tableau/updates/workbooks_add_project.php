<?php namespace Elipce\Tableau\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class WorkbooksAddProject
 * @package Elipce\Tableau\Updates
 */
class WorkbooksAddProject extends Migration
{

    public function up()
    {
        Schema::table('elipce_tableau_workbooks', function ($table) {
            $table->string('project_name');
        });
    }

    public function down()
    {
        Schema::table('elipce_tableau_workbooks', function ($table) {
            $table->dropColumn('project_name');
        });
    }

}
