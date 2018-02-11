<?php namespace Hao\Dictionary\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;


/**
 * Initial the table hao_dictionary_grammaticalgenders
 */
Db::table('hao_dictionary_grammaticalgenders')->insert(['name' => '阴性',]);
Db::table('hao_dictionary_grammaticalgenders')->insert(['name' => '阳性',]);


/**
 * Initial the table hao_dictionary_languages
 */
Db::table('hao_dictionary_languages')->insert(['name' => '英语',]);
Db::table('hao_dictionary_languages')->insert(['name' => '法语',]);


/**
 * Initial the table hao_dictionary_partofspeeches
 */
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '名词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '动词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '形容词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '副词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '冠词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '连词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '叹词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '介词',]);
Db::table('hao_dictionary_partofspeeches')->insert(['name' => '带词',]);


/**
 *  Initial the table hao_dictionary_partofspeeches
 */
Db::table('hao_dictionary_singularandplurals')->insert(['name' => '单数',]);
Db::table('hao_dictionary_singularandplurals')->insert(['name' => '复数',]);



