<?php namespace Hao\Cv\Models;

use Model;

/**
 * Formation Model
 */
class Formation extends Model
{
    /**
     * @var array Validation rules
     */
    public $rules = [
        'cv_id'           => 'required',
        'start_date'     => 'required',
        'end_time'          => 'required',
        'school'     => 'required',
        'background'     => 'required',
        'domain'     => 'required',
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'start_date'    => 'hao.cv::lang.models.cv.start_date',
        'end_time'      => 'hao.cv::lang.models.cv.end_date',
        'school'        => 'hao.cv::lang.models.cv.school',
        'background'        => 'hao.cv::lang.models.cv.background',
        'domain'        => 'hao.cv::lang.models.cv.domain',
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
    public $table = 'hao_cv_formations';

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
