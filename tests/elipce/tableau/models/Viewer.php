<?php namespace Elipce\Tableau\Models;

use October\Rain\Database\Model;

/**
 * Model Viewer
 */
class Viewer extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_tableau_viewers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'external_id'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'user' => ['RainLab\User\Models\User', 'key' => 'email', 'otherKey' => 'name']
    ];
    public $belongsTo = [
        'site' => ['Elipce\Tableau\Models\Site', 'key' => 'site_id']
    ];
    public $belongsToMany = [
        'groups' => [
            'Elipce\Tableau\Models\Group',
            'table'    => 'elipce_tableau_viewers_groups',
            'key'      => 'viewer_id',
            'otherKey' => 'group_id'
        ]
    ];
}