<?php namespace Hao\Socialnetwork\Models;

use Model;

/**
 * TwitterUser Model
 */
class TwitterUser extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_socialnetwork_twitter_users';




    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'profile_location_id',
        'name',
        'screen_name',
        'location',
        'description',
        'url',
        'protected',
        'followers_count',
        'friends_count',
        'listed_count',
        'created_at',
        'favourites_count',
        'utc_offset',
        'time_zone',
        'geo_enabled',
        'verified',
        'statuses_count',
        'lang',
        'contributors_enabled',
        'is_translator',
        'is_translation_enabled',
        'profile_background_color',
        'profile_background_image_url',
        'profile_background_image_url_https',
        'profile_background_tile',
        'profile_image_url',
        'profile_image_url_https',
        'profile_link_color',
        'profile_sidebar_border_color',
        'profile_sidebar_fill_color',
        'profile_text_color',
        'profile_use_background_image',
        'has_extended_profile',
        'default_profile',
        'default_profile_image',
        'following',
        'follow_request_sent',
        'notifications',
        'translator_type',
        'API'
    ];

    /**
     * @var string Models will assume that each table has a primary key column named id.
     * You may define a $primaryKey property to override this convention.
     */
    protected $primaryKey = 'id';

    /**
     * @var bool Models will assume that the primary key is an incrementing integer value,
     * which means that by default the primary key will be cast to an integer automatically.
     * If you wish to use a non-incrementing or a non-numeric primary key you must set the public $incrementing property to false.
     */
    public $incrementing = false;

    /**
     * @var bool By default, a model will expect created_at and updated_at columns to exist on your tables.
     * If you do not wish to have these columns managed automatically,
     * set the $timestamps property on your model to false:
     */
    public $timestamps = false;



    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'user_static' =>[
            '\Hao\Socialnetwork\Models\TwitterUserStatistic',
            'key'       => 'id',
            'otherkey'  => 'id',
        ]
    ];
    public $belongsTo = [
        'profile_location' =>[
            '\Hao\Socialnetwork\Models\TwitterProfileLocation',
            'key'       => 'profile_location_id',
            'otherkey'  => 'id',
        ]
    ];
    public $belongsToMany = [
        'followers' =>  [
            'Hao\Socialnetwork\Models\TwitterUser',
            'table' =>  'hao_socialnetwork_twitter_followers',
            'key'   =>  'user_id',
            'otherKey'  =>  'follower_id'
        ],

        'friends' =>  [
            'Hao\Socialnetwork\Models\TwitterUser',
            'table' =>  'hao_socialnetwork_twitter_followers',
            'key'   =>  'follower_id',
            'otherKey'  =>  'user_id'
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
