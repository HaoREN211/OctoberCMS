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
