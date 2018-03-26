<?php namespace Hao\Socialnetwork\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Hao\Socialnetwork\Models\VideoType as HaoVideoType;

class InitVideoTypes extends Migration
{
    public function up()
    {
        HaoVideoType::create([
            'id' => 1,
            'name'  =>  'xvideos',
            'URL'   =>  'https://www.xvideos.com'
        ]);
    }
}
