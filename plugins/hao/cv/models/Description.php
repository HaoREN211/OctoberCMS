<?php namespace Hao\Cv\Models;

use Model;

/**
 * Description Model
 */
class Description extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_cv_descriptions';

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
