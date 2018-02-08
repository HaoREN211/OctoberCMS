<?php namespace Elipce\BiPage\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateSnippetsTable
 * @package Elipce\BiPage\Updates
 */
class CreateSnippetsTable extends Migration
{

    public function up()
    {
        Schema::create('elipce_bipage_snippets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('id');
            $table->string('name');
            $table->text('code');
            $table->boolean('public')->default(false);
            $table->unsignedInteger('collection_id');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                ->references('id')
                ->on('elipce_bipage_dashboards')
                ->onDelete('cascade');

            $table->foreign('collection_id')
                ->references('id')
                ->on('elipce_bipage_collections')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('elipce_bipage_snippets');
    }

}
