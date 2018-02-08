<?php namespace Elipce\BiPage\Models;

use October\Rain\Database\Model;

/**
 * Model Bookmark
 */
class Bookmark extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bipage_bookmarks';

    /**
     * @var bool Disable timestamps
     */
    public $timestamps = false;

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['id'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'page_id',
        'user_id',
        'portal_id'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'user'   => ['Rainlab\User\Models\User', 'key', 'user_id'],
        'page'   => ['Elipce\BiPage\Models\Page', 'key', 'page_id'],
        'portal' => ['Elipce\Multisite\Models\Portal', 'key', 'portal_id'],
    ];
}