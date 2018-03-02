<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/1
 * Time: 10:17
 */
namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Hao\Socialnetwork\Models\TwitterToken;

class TwitterInitTokenTable extends Migration
{
    public function up()
    {
        TwitterToken::create(
            [
                'consumer_key'  => '1',
                'consumer_secret'   => '2',
                'access_token'  => '3',
                'access_token_secret'   =>  '4',
                'base64_encoded_token_credentials'  =>  '5',
                'token' =>  '6',
                'token_type'    =>  '7',
                'social_network'    =>  'Twitter'
            ]
        );
    }

    public function down(){

    }
}
