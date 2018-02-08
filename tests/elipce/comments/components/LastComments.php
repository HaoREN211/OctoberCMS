<?php namespace Elipce\Comments\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page as CmsPage;
use Elipce\Comments\Models\Comment;
use Elipce\Multisite\Facades\Multisite;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use RainLab\User\Facades\Auth;

class LastComments extends ComponentBase
{
    /**
     * Component attributes
     * @var
     */
    public $comments;
    public $pagination;
    public $sorting;
    public $redirectPage;

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.comments::lang.components.last_comments',
            'description' => 'elipce.comments::lang.components.last_comments_description'
        ];
    }

    /**
     * Component properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'pagination' => [
                'title' => 'elipce.comments::lang.components.pagination',
                'description' => 'elipce.comments::lang.components.pagination_description',
                'default' => '3',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'elipce.comments::lang.components.pagination_validation'
            ],
            'sorting' => [
                'title' => 'elipce.comments::lang.components.sorting',
                'description' => 'elipce.comments::lang.components.sorting_description',
                'type' => 'dropdown',
                'default' => 'desc'
            ],
            'redirectPage' => [
                'title' => 'elipce.comments::lang.backend.redirect_page',
                'description' => 'elipce.comments::lang.components.redirect_page_description',
                'type' => 'dropdown',
                'default' => 'page'
            ]
        ];
    }

    /**
     * Populate redirect page dropdown
     * @return mixed
     */
    public function getRedirectPageOptions()
    {
        return CmsPage::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Populate sorting options dropdown
     * @return mixed
     */
    public function getSortingOptions()
    {
        return [
            'asc' => Lang::get('elipce.comments::lang.components.sorting_asc'),
            'desc' => Lang::get('elipce.comments::lang.components.sorting_desc')
        ];
    }

    /**
     * Prepare variables before process
     */
    public function prepareVars()
    {
        // Check if user is connected
        if (!$this->page['user'])
            $this->page['user'] = Auth::getUser();

        // Check if portal component is loaded
        if (!$this->page['portal'])
            $this->page['portal'] = Multisite::getPortal();

        $this->pagination = $this->property('pagination');
        $this->sorting = $this->property('sorting');
        $this->redirectPage = $this->property('redirectPage');
    }

    /**
     * Load last comments
     * @return mixed
     */
    public function loadComments()
    {
        // Get last comments from current portal
        $comments = Comment::published()
            ->applyPortal($this->page['portal']->id)
            ->orderBy('created_at', $this->sorting)
            ->limit($this->pagination)
            ->get();

        // Only allowed comments
        return $comments->filter(function ($c) {
            return $c->page->canAccess($this->page['user']);
        });
    }

    /**
     * On component run
     */
    public function onRun()
    {
        // Inject CSS
        $this->addCss('assets/css/last_comments.css');
        // Inject data
        $this->prepareVars();
        $this->comments = $this->page['comments'] = $this->loadComments();
    }

}