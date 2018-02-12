<?php namespace Hao\Event\Updates;

use Illuminate\Support\Facades\DB;

DB::table('hao_event_events')
    ->insert([
            'name' => 'Valence 2018 新年小聚',
            'description' => '
            时间: 2018年2月16号礼拜五
            地点：4 rue champville 
            点击最下面的按钮能自动打开地图',
            'happend_date' => '2018-02-16',
            'heppend_place' => 'https://www.google.fr/maps/place/4+Rue+Champville,+26000+Valence/@44.924669,4.8942245,17z/data=!3m1!4b1!4m5!3m4!1s0x47f558285c274afd:0xb286b453852df7b2!8m2!3d44.924669!4d4.8964132',
            'created_at' => '2018-02-12',
            'updated_at' => '2018-02-12',
        ]
    );
