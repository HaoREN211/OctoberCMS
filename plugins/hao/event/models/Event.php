<?php namespace Hao\Event\Models;

use Model;

/**
 * Event Model
 */
class Event extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_event_events';

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
        'things' => [
            'Hao\Event\Models\Thing',
            'key'      => 'event_id',
            'otherKey' => 'id'
        ]
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        'users' => [
            'Hao\Event\Models\User',
            'table'    => 'hao_event_events_users',
            'key'      => 'event_id',
            'otherKey' => 'user_id'
        ],
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => 'System\Models\File',
    ];
}
