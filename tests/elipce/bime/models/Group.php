<?php namespace Elipce\Bime\Models;

use Backend\Models\User as BackendUser;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Model Group
 */
class Group extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bime_groups';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name', 'external_id'];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'viewers' => ['Elipce\Bime\Models\Viewer', 'key' => 'group_id'],
        'viewers_count' => ['Elipce\Bime\Models\Viewer', 'key' => 'group_id', 'count' => true]
    ];
    public $belongsTo = [
        'account' => ['Elipce\Bime\Models\Account', 'key' => 'account_id']
    ];
    public $belongsToMany = [
        'dashboards' => ['Elipce\Bime\Models\Dashboard', 'table' => 'elipce_bime_subscriptions', 'key' => 'group_id', 'otherKey' => 'dashboard_id']
    ];

    /**
     * Scope a query to only include allowed groups for a given backend user.
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

        return $query->whereIn('id', $user->bime_groups);
    }

    /**
     * Scope a query to only include groups of a given account.
     *
     * @param Builder $query
     * @param int $accountId
     * @return Builder
     */
    public function scopeApplyAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    /**
     * Mutator for full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->account->name . ' \ ' . $this->name;
    }
}