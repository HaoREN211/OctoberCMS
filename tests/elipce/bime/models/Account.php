<?php namespace Elipce\Bime\Models;

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
    use \October\Rain\Database\Traits\Encryptable;

    /**
     * Validation rules
     */
    public $rules = [
        'name' => 'required',
        'url' => 'required|url|unique:elipce_bime_accounts',
        'token' => 'required:create'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.bime::lang.backend.accounts.name_label',
        'url' => 'elipce.bime::lang.backend.accounts.url_label',
        'token' => 'elipce.bime::lang.backend.accounts.token_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bime_accounts';

    /**
     * @var array Encryptable fields
     */
    protected $encryptable = ['token'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'url',
        'token'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'groups' => ['Elipce\Bime\Models\Group', 'key' => 'account_id'],
        'dashboards' => ['Elipce\Bime\Models\Dashboard', 'key' => 'account_id'],
        'viewers' => ['Elipce\Bime\Models\Viewer', 'key' => 'account_id']
    ];
}