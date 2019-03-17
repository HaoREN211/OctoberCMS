<?php namespace Hao\Cv\Models;

use Model;

/**
 * Experience Model
 */
class Experience extends Model
{
    /**
     * @var array Validation rules
     */
    public $rules = [
        'cv_id'           => 'required',
        'start_date'     => 'required',
        'end_time'          => 'required',
        'enterprise'     => 'required',
        'position'     => 'required',
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'start_date'    => 'hao.cv::lang.models.cv.start_date',
        'end_time'      => 'hao.cv::lang.models.cv.end_date',
        'enterprise'        => 'hao.cv::lang.models.experience.enterprise',
        'position'        => 'hao.cv::lang.models.experience.position',
    ];

    /**
     * @var new version of message error
     */
    public $customMessages = [
        'required' => '\':attribute\'是必需的'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hao_cv_experiences';

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
        'cv' => [
            '\Hao\Cv\Models\Cv',
            'key' => 'cv_id',
            'otherkey' => 'id',
        ],
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
