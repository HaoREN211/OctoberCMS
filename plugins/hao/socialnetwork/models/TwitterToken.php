<?php namespace Hao\Socialnetwork\Models;

use Model;

/**
 * TwitterToken Model
 */
class TwitterToken extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_socialnetwork_twitter_tokens';

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
        'user' => ['Hao\Socialnetwork\Models\TwitterUser',
            'key' => 'twitter_id',
            'otherKey' => 'id',
            'table'=>'hao_socialnetwork_twitter_users'
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
