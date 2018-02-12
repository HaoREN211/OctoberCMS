<?php namespace Hao\Event\Models;

use Model;

/**
 * Thing Model
 */
class Thing extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_event_things';

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
        'user' => [
            'Hao\Event\Models\User',
            'key'      => 'user_id',
            'otherKey' => 'id'
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
