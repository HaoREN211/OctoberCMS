<?php namespace Elipce\Analytics\Models;

use October\Rain\Database\Model;

/**
 * Model View
 */
class View extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_analytics_views';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type',
        'external_id',
        'property_id'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'property' => ['Elipce\Analytics\Models\Property', 'key' => 'property_id']
    ];

    /**
     * Mutator for full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->property->name} / {$this->name}";
    }
}