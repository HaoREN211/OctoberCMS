<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Model;

/**
 * Account Model
 */
class Account extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Encryptable;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'url' => 'required|url|unique:elipce_limesurvey_accounts',
        'login' => 'required',
        'password' => 'required:create'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.limesurvey::lang.backend.accounts.name_label',
        'url' => 'elipce.limesurvey::lang.backend.accounts.url_label',
        'login' => 'elipce.limesurvey::lang.backend.accounts.username_label',
        'password' => 'elipce.limesurvey::lang.backend.accounts.password_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_accounts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'login',
        'password',
        'url'
    ];

    /**
     * @var array Encryptable fields
     */
    protected $encryptable = ['password'];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'portals' => ['Elipce\Multisite\Models\Portal', 'key' => 'limesurvey_account_id']
    ];
}