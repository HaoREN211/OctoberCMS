<?php namespace Hao\Dictionary\Models;

use Model;

/**
 * Translation Model
 */
class Translation extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_dictionary_translations';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [

        'vocabulary' => [
            '\Hao\Dictionary\Models\Vocabulary',
            'key' => 'vocabulary_id',
            'otherkey' => 'id',
        ],

        'grammaticalgender' => [
            '\Hao\Dictionary\Models\Grammaticalgender',
            'key' => 'grammaticalgender_id',
            'otherkey' => 'id',
        ],

        'partofspeech' => [
            '\Hao\Dictionary\Models\Partofspeech',
            'key' => 'partofspeeche_id',
            'otherkey' => 'id',
        ],

        'singularandplural' => [
            '\Hao\Dictionary\Models\Singularandplural',
            'key' => 'singularandplural_id',
            'otherkey' => 'id',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
