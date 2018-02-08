<?php namespace Elipce\News\Models;

use October\Rain\Database\Model;
use RainLab\User\Models\User;

/**
 * News Model
 */
class News extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'text' => 'required',
        'thumbnail' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.news::lang.backend.news.name_label',
        'text' => 'elipce.news::lang.backend.news.text_label',
        'thumbnail' => 'elipce.news::lang.news.portals.thumbnail_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_news_news';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'text'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'page' => ['Elipce\BiPage\Models\Page', 'key' => 'page_id'],
        'portal' => ['Elipce\Multisite\Models\Portal', 'key' => 'portal_id']
    ];
    public $attachOne = ['thumbnail' => 'System\Models\File'];

    /**
     * Check if a given user can access to the news.
     *
     * @param User $user
     * @return bool
     */
    public function canAccess(User $user)
    {
        if ($this->page !== null) {
            return $this->page->canAccess($user);
        } else {
            return true;
        }
    }
}