<?php namespace Hao\Socialnetwork\Models;

use Model;

/**
 * Video Model
 */
class Video extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_socialnetwork_videos';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['url',
        'type',
        'name',
        'is_liked',
        'is_watched'];

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
    public $incrementing = true;

    /**
     * @var bool By default, a model will expect created_at and updated_at columns to exist on your tables.
     * If you do not wish to have these columns managed automatically,
     * set the $timestamps property on your model to false:
     */
    public $timestamps = true;

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'video_type'    =>  [
            'Hao\Socialnetwork\Models\VideoType',
            'key'   => 'type',
            'otherKey' => 'id'
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
