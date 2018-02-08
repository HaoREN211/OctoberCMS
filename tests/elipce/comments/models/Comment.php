<?php namespace Elipce\Comments\Models;

use October\Rain\Database\Builder;
use October\Rain\Support\Facades\Markdown;
use Backend\Models\User as BackendUser;
use October\Rain\Database\Model;
use Elipce\BiPage\Models\Page;

/**
 * Model Comment
 */
class Comment extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Nullable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_comments_comments';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'page_id',
        'portal_id',
        'pid',
        'author_id',
        'published',
        'content'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array Nullable attributes
     */
    protected $nullable = ['pid'];

    /**
     * Relations
     */
    public $belongsTo = [
        'author' => ['RainLab\User\Models\User', 'key' => 'author_id'],
        'page'   => ['Elipce\BiPage\Models\Page', 'key' => 'page_id'],
        'portal' => ['Elipce\Multisite\Models\Portal', 'key' => 'portal_id']
    ];

    /**
     * Before the model is saved.
     */
    public function beforeSave()
    {
        /*
         * Generate HTML content from Markdown
         */
        $content = strip_tags(trim($this->content));
        $this->content_html = Markdown::parse($content);
    }

    /**
     * Scope a query to only include published comments.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    /**
     * Scope a query to only include comments of a given portal.
     *
     * @param Builder $query
     * @param int $portalId
     * @return Builder
     */
    public function scopeApplyPortal($query, $portalId)
    {
        return $query->where('portal_id', $portalId);
    }

    /**
     * Scope a query to only include comments of a given page
     *
     * @param Builder $query
     * @param int $pageId
     * @return Builder
     */
    public function scopeApplyPage($query, $pageId)
    {
        return $query->where('page_id', $pageId);
    }

    /**
     * Scope a query to only include allowed comments
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

        $allowed = Page::whereIn('collection_id', $user->collections)->lists('id');

        return $query->whereIn('page_id', $allowed)->applyPortal($user->portal->id);
    }

    /**
     * Publish a comment and all his children.
     * @param $id array|int
     */
    public static function publish($id)
    {
        if (is_array($id)) {
            array_walk($id, [get_class(), 'publish']);
        } else {
            $children = self::where('pid', $id)->lists('id');
            self::find($id)->update(['published' => true]);
            self::publish($children);
        }
    }

    /**
     * Unpublish a comment and all his children.
     * @param $id array|int
     */
    public static function unpublish($id)
    {
        if (is_array($id)) {
            array_walk($id, [get_class(), 'unpublish']);
        } else {
            $children = self::where('pid', $id)->lists('id');
            self::find($id)->update(['published' => false]);
            self::unpublish($children);
        }
    }
}