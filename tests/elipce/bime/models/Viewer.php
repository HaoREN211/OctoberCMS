<?php namespace Elipce\Bime\Models;

use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Model Viewer
 */
class Viewer extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bime_viewers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'access_token',
        'external_id',
        'login'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'user' => ['RainLab\User\Models\User', 'key' => 'email', 'otherKey' => 'login']
    ];
    public $belongsTo = [
        'group'   => ['Elipce\Bime\Models\Group', 'key' => 'group_id'],
        'account' => ['Elipce\Bime\Models\Account', 'key' => 'account_id']
    ];

    /**
     * Scope a query to only include allowed viewers
     * for a given backend user.
     *
     * @param Builder $query
     * @param BackendUser $user
     * @return Builder
     */
    public function scopeIsAllowed($query, BackendUser $user)
    {
        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->whereIn('group_id', $user->bime_groups);
    }

    /**
     * Mutator for full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->account->name . ' \ ' . $this->login;
    }

    /**
     * Mutator for activated attribute (has a frontend user).
     *
     * @return boolean
     */
    public function getActivatedAttribute()
    {
        return $this->user != null;
    }
}