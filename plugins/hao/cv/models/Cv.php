<?php namespace Hao\Cv\Models;

use Model;

/**
 * Cv Model
 */
class Cv extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_cv_cvs';

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
        'formation' => [
            '\Hao\Cv\Models\Formation',
            'key' => 'cv_id',
            'otherkey' => 'id',
        ],
        'experience' => [
            '\Hao\Cv\Models\Experience',
            'key' => 'cv_id',
            'otherkey' => 'id',
        ],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
