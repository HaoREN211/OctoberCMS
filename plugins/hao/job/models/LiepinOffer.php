<?php namespace Hao\Job\Models;

use Model;

/**
 * LiepinOffer Model
 */
class LiepinOffer extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_job_liepin_offers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'title',
        'enterprise',
        'department',
        'filiere',
        'responsable',
        'man',
        'salary',
        'location',
        'response'
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
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
