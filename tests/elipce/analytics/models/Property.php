<?php namespace Elipce\Analytics\Models;

use October\Rain\Database\Model;

/**
 * Model Property
 */
class Property extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_analytics_properties';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'external_id',
        'account_id'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'views' => ['Elipce\Analytics\Models\View', 'key' => 'property_id']
    ];
    public $belongsTo = [
        'account' => ['Elipce\Analytics\Models\Account', 'key' => 'account_id']
    ];
}