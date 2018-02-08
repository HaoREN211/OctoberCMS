<?php namespace Elipce\Tableau\Models;

use October\Rain\Database\Model;

/**
 * Model Group
 */
class Group extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_tableau_groups';

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
    public $belongsTo = [
        'site' => ['Elipce\Tableau\Models\Site', 'key' => 'site_id']
    ];
    public $belongsToMany = [
        'viewers'       => [
            'Elipce\Tableau\Models\Viewer',
            'table'    => 'elipce_tableau_viewers_groups',
            'key'      => 'group_id',
            'otherKey' => 'viewer_id'
        ],
        'viewers_count' => [
            'Elipce\Tableau\Models\Viewer',
            'table'    => 'elipce_tableau_viewers_groups',
            'key'      => 'group_id',
            'otherKey' => 'viewer_id',
            'count'    => true
        ]
    ];
}