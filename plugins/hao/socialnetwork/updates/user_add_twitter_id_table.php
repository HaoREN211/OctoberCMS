<?php namespace Hao\Socialnetwork\Updates;
use Schema;
use October\Rain\Database\Updates\Migration;

class UserAddTwitterIdTable extends Migration{
    public function up()
    {
        Schema::table('backend_users', function ($table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('twitter_id')->unsigned()->nullable();

            $table->foreign('twitter_id')
                ->references('id')
                ->on('hao_socialnetwork_twitter_users');
        });
    }

    public function down(){

    }
}
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/2/26
 * Time: 20:33
 */
