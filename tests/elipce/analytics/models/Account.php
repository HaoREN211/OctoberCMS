<?php namespace Elipce\Analytics\Models;

use October\Rain\Database\Model;

/**
 * Model Account
 */
class Account extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'   => 'required',
        'email'  => 'required|email|unique:elipce_analytics_accounts',
        'p12key' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors.
     */
    public $attributeNames = [
        'name'   => 'elipce.analytics::lang.backend.accounts.name_label',
        'email'  => 'elipce.analytics::lang.backend.accounts.email_label',
        'p12key' => 'elipce.analytics::lang.backend.accounts.p12key_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_analytics_accounts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['email', 'name'];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'properties' => [
            'Elipce\Analytics\Models\Property',
            'key' => 'account_id'
        ]
    ];
    public $hasManyThrough = [
        'views' => [
            'Elipce\Analytics\Models\View',
            'key'        => 'account_id',
            'through'    => 'Elipce\Analytics\Models\Property',
            'throughKey' => 'property_id'
        ]
    ];
    public $attachOne = [
        'p12key' => [
            'System\Models\File',
            'public' => false
        ]
    ];
}