<?php namespace Hao\Socialnetwork\Models;

use Model;

/**
 * TwitterUserStatistic Model
 */
class TwitterUserStatistic extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_socialnetwork_twitter_user_statistics';

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
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
