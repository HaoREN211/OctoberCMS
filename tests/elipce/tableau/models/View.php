<?php namespace Elipce\Tableau\Models;

use October\Rain\Database\Model;

/**
 * Model View
 */
class View extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_tableau_views';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'contentUrl',
        'external_id'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'site' => ['Elipce\Tableau\Models\Site', 'key' => 'site_id'],
        'workbook' => ['Elipce\Tableau\Models\Workbook', 'key' => 'workbook_id']
    ];
}