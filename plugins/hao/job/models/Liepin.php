<?php namespace Hao\Job\Models;

use Model;

/**
 * Liepin Model
 */
class Liepin extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_job_liepins';

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
