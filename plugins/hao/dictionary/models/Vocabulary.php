<?php namespace Hao\Dictionary\Models;

use Model;

/**
 * Vocabulary Model
 */
class Vocabulary extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_dictionary_vocabularies';

    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'                  => 'required',
        'language'              => 'required'
    ];


    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name'      => 'hao.dictionary::lang.backend.dictionary.name',
        'language' => 'hao.dictionary::lang.backend.dictionary.language'
    ];

    /**
     * @var new version of message error
     */
    public $customMessages = [
        'required' => '\':attribute\'是必需的'
    ];




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
    public $hasMany = [
        'translation' => [
            '\Hao\Dictionary\Models\Translation',
            'key' => 'vocabulary_id',
            'otherkey' => 'id',
        ]
    ];
    public $belongsTo = [
        'language' => [
            '\Hao\Dictionary\Models\Language',
            'key' => 'language_id',
            'otherkey' => 'id',
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
