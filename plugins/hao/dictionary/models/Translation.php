<?php namespace Hao\Dictionary\Models;

use Model;

/**
 * Translation Model
 */
class Translation extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'                  => 'required',
        'language'              => 'required',
        'description'           => 'required',
        'grammaticalgender'     => 'required',
        'partofspeech'          => 'required',
        'singularandplural'     => 'required',
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'                  => 'hao.dictionary::lang.backend.translation.name',
        'language'              => 'hao.dictionary::lang.backend.translation.language',
        'description'           => 'hao.dictionary::lang.backend.translation.description',
        'grammaticalgender'     => 'hao.dictionary::lang.backend.translation.grammaticalgender',
        'partofspeech'          => 'hao.dictionary::lang.backend.translation.partofspeech',
        'singularandplural'     => 'hao.dictionary::lang.backend.translation.singularandplural',
    ];

    /**
     * @var new version of message error
     */
    public $customMessages = [
        'required' => '\':attribute\'是必需的'
    ];

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
