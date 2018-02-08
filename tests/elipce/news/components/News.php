<?php namespace Elipce\News\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page as CmsPage;
use Illuminate\Support\Facades\Lang;
use October\Rain\Exception\ApplicationException;

/**
 * Class News
 * @package Elipce\News\Components
 */
class News extends ComponentBase
{
    /**
     * Component attributes
     * @var
     */
    public $news;
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
            'name' => 'elipce.news::lang.component.news',
            'description' => 'elipce.news::lang.component.news_description'
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
                'title' => 'elipce.news::lang.component.pagination',
                'description' => 'elipce.news::lang.component.pagination_description',
                'default' => '5',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'elipce.news::lang.component.pagination_validation'
            ],
            'sorting' => [
                'title' => 'elipce.news::lang.component.sorting',
                'description' => 'elipce.news::lang.component.sorting_description',
                'type' => 'dropdown',
                'default' => 'desc'
            ],
            'redirectPage' => [
                'title' => 'elipce.news::lang.component.redirect_page',
                'description' => 'elipce.bipage::lang.components.redirect_page_description',
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
            'asc' => Lang::get('elipce.news::lang.component.sorting_asc'),
            'desc' => Lang::get('elipce.news::lang.component.sorting_desc')
        ];
    }

    /**
     * Component validation
     * @throw ApplicationException
     */
    public function validate()
    {
        // Check if portal component is loaded
        if ($this->page['portal'] === null)
            throw new ApplicationException('Portal component not found !');

        // Check if user is connected
        if ($this->page['user'] === null)
            throw new ApplicationException('No connected user found !');
    }

    /**
     * Prepare variables before process
     */
    public function prepareVars()
    {
        $this->pagination = $this->property('pagination');
        $this->sorting = $this->property('sorting');
        $this->redirectPage = $this->property('redirectPage');
    }

    /**
     * Load news for current portal
     * @return mixed
     */
    private function loadNews()
    {
        // Query
        $posts = $this->page['portal']
            ->news()
            ->orderBy('created_at', $this->sorting)
            ->limit($this->pagination)
            ->get();

        // Only allowed news
        return $posts->filter(function ($post) {
            return $post->canAccess($this->page['user']);
        });
    }

    /**
     * On component run
     */
    public function onRun()
    {
        // Inject CSS
        $this->addCss('assets/css/news.css');
        // Inject data
        $this->validate();
        $this->prepareVars();
        $this->news = $this->page['news'] = $this->loadNews();
    }
}