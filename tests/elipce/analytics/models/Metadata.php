<?php namespace Elipce\Analytics\Models;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Model Metadata
 */
class Metadata extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_analytics_metadata';

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
        'description',
        'code',
        'metadata_group_id'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'group' => ['Elipce\Analytics\Models\MetadataGroup', 'key' => 'metadata_group_id']
    ];

    /**
     * Scope a query to only include metrics metadata.
     *
     * @param Builder $query
     * @return Builder mixed
     */
    public function scopeMetrics($query)
    {
        return $query->where('type', 'METRIC');
    }

    /**
     * Scope a query to only include dimension metadata.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeDimensions($query)
    {
        return $query->where('type', 'DIMENSION');
    }
}