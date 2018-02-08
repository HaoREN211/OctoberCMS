<?php namespace Elipce\SSO\Models;

use October\Rain\Database\Model;

/**
 * Log Back-end Controller
 */
class Log extends Model
{

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'elipce_sso_logs';

    /**
     * @var array The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'ip',
        'email',
        'result'
    ];
}
