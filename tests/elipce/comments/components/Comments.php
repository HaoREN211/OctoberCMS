<?php namespace Elipce\Comments\Components;

use Illuminate\Support\Facades\DB;
use October\Rain\Exception\ApplicationException;
use RainLab\User\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Cms\Classes\ComponentBase;
use Elipce\Comments\Models\Comment;
use October\Rain\Support\Collection;

/**
 * Class Comments
 * @package Elipce\Comments\Components
 */
class Comments extends ComponentBase
{
    /**
     * A collection of comments to display.
     * @var Collection
     */
    public $comments;

    /**
     * Parameter to use for the comment reply depth.
     * @var string
     */
    public $depth;

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.comments::lang.components.comments',
            'description' => 'elipce.comments::lang.components.comments_description'
        ];
    }

    /**
     * Component properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'depth' => [
                'title' => 'elipce.comments::lang.components.depth',
                'description' => 'elipce.comments::lang.components.depth_description',
                'default' => '0',
                'type' => 'string',
                'required' => true,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'elipce.comments::lang.components.depth_validation_message'
            ],
        ];
    }

    /**
     * Component validation
     * @throws ApplicationException
     */
    public function validate()
    {
        // Check if portal component is loaded
        if ($this->page['portal'] === null)
            throw new ApplicationException('Portal component not found !');

        // Check if bipage component is loaded
        if ($this->page['bipage'] === null)
            throw new ApplicationException('BiPage component not found !');
    }

    /**
     * Prepare variables before process
     */
    protected function prepareVars()
    {
        $this->depth = $this->page['depth'] = $this->property('depth');
        $this->comments = $this->page['comments'] = $this->loadComments();
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        // Check if user is connected
        if ($this->page['user'] !== null) {
            // Inject assets
            $this->addCss('assets/css/comments.css');
            $this->addJs('assets/js/comments.js');
            // Inject data
            $this->validate();
            $this->prepareVars();
        }
    }

    /**
     * AJAX Event : comment button is clicked
     */
    public function onComment()
    {
        // Add comment reply depth to template
        $this->depth = $this->page['depth'] = $this->property('depth');
        $this->page['level'] = post('level');

        // Create or update
        if($id = post('id')) {
            $this->updateComment($id);
        } else {
            $this->createComment();
        }
    }

    /**
     * Create a new comment
     * @return void
     */
    protected function createComment()
    {
        // Prepare params
        $params = [
            'portal_id' =>  post('portal_id'),
            'page_id' => post('page_id'),
            'pid' => post('pid'),
            'author_id' => Auth::getUser()->id,
            'content' => post('content')
        ];

        try {
            // Create comment and update component
            $comment = Comment::create($params);
            $this->comment = $this->page['comment'] = $comment;
        } catch (\Exception $e) {
            $this->page['message'] = e(trans('elipce.comments::lang.messages.create_error'));
        }
    }

    /**
     * Update a comment
     * @param $id
     * @return void
     */
    protected function updateComment($id)
    {
        // Check content
        if($content = post('content')) {
            try {
                $comment = Comment::find($id);
                $comment->content = $content;
                $comment->save();
                // Update component
                $this->comment = $this->page['comment'] = $comment;
            } catch (\Exception $e) {
                $this->page['message'] = e(trans('elipce.comments::lang.messages.edit_error'));
            }
        }
    }

    /**
     * AJAX Event : edit button is clicked
     * @return string|void
     */
    public function onEditComment()
    {
        // Get comment and user
        $id = post('id');
        $user = Auth::getUser();
        $comment = Comment::find($id);

        // If user is author return comment content
        if($user->id == $comment->author_id) {
            return json_encode([
                'id' => $id,
                'content' => $comment->content
            ]);
        }
        else {
            return null;
        }
    }

    /**
     * AJAX Event : delete button is clicked
     * @return int|null
     */
    public function onDeleteComment()
    {
        // Get comment and user
        $id = post('id');
        $user = Auth::getUser();
        $comment = Comment::find($id);

        // If user is author delete comment
        if($user->id == $comment->author_id) {
            $comment->delete();
            return $id;
        }
        else {
            return null;
        }
    }

    /**
     * Fetch all comments
     * @return mixed
     */
    protected function loadComments()
    {
        return Comment::published()
            ->where('portal_id', $this->page['portal']->id)
            ->where('page_id', $this->page['bipage']->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}