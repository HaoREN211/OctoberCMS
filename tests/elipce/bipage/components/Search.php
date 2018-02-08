<?php namespace Elipce\BiPage\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page as CmsPage;
use Elipce\BiPage\Models\Page;
use Elipce\Multisite\Facades\Multisite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use RainLab\User\Facades\Auth;

/**
 * Class Search
 * @package Elipce\Search\Components
 */
class Search extends ComponentBase
{
    /**
     * Component attributes
     * @var null
     */
    public $results = null;
    public $redirectPage;
    public $sorting;
    public $pagination;

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.bipage::lang.component.search',
            'description' => 'elipce.bipage::lang.component.search_description'
        ];
    }

    /**
     * Component properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'redirectPage' => [
                'title' => 'elipce.bipage::lang.component.redirect_page',
                'description' => 'elipce.bipage::lang.component.redirect_page_description',
                'type' => 'dropdown',
                'default' => 'page'
            ],
            'pagination' => [
                'title' => 'elipce.bipage::lang.component.pagination',
                'description' => 'elipce.bipage::lang.component.pagination_description',
                'default' => '5',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'elipce.bipage::lang.component.pagination_validation'
            ],
            'sorting' => [
                'title' => 'elipce.bipage::lang.component.sorting',
                'description' => 'elipce.bipage::lang.component.sorting_description',
                'type' => 'dropdown',
                'default' => 'desc'
            ]
        ];
    }

    /**
     * Populate redirect page dropdown
     * @return array
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
            'asc' => Lang::get('elipce.bipage::lang.component.sorting_asc'),
            'desc' => Lang::get('elipce.bipage::lang.component.sorting_desc')
        ];
    }

    /**
     * Prepare variable before process
     */
    public function prepareVars()
    {
        $this->redirectPage = $this->property('redirectPage');
        $this->pagination = $this->property('pagination');
        $this->sorting = $this->property('sorting');

        // Init results
        $this->page['results'] = $this->results;
    }

    /**
     * On component run
     */
    public function onRun()
    {
        // Inject variables
        $this->prepareVars();
        // Inject assets
        $this->addCss('assets/css/search.css');
        $this->addCss('assets/css/list.css');
        $this->addJs('assets/js/search.js');
    }

    /**
     * Search pages from keywords
     *
     * @param $keywords string
     * @return array
     */
    private function search($keywords)
    {
        if (empty($keywords))
            return new Collection();

        // Fetch user and portal
        $user = Auth::getUser();
        $portal = Multisite::getPortal();

        // Build query
        $query = Page::applyPortal($portal->id)->published();

        // Lazy loading
        $query->with(['folders' => function ($query) use ($portal) {
            return $query->where('portal_id', $portal->id);
        }]);

        // Return matching allowed pages
        return $query->get()->filter(function ($page) use ($user, $keywords) {
            return $page->canAccess($user) && $this->match($keywords, $page);
        });
    }

    /**
     * Check matching keywords on a page
     *
     * @param $keywords
     * @param $page
     * @return bool
     */
    private function match($keywords, $page)
    {
        // Extract keywords
        $aKeywords = explode(' ', strtolower($keywords));

        // Check each keyword
        foreach ($aKeywords as $keyword) {
            try {
                // Check matching page's dashboard names
                $page->dashboards->each(function ($dashboard) use ($keyword) {
                    if (preg_match('/' . $keyword . '/', strtolower($dashboard->name)))
                        throw new \Exception;
                });
            } catch (\Exception $e) {
                return true;
            }
            // Check matching page name or excerpt
            return preg_match('/' . $keyword . '/', strtolower($page->name))
            || preg_match('/' . $keyword . '/', strtolower($page->excerpt));
        }

        // No matching keyword
        return false;
    }

    /**
     * AJAX Event : Redirect to results page with params
     */
    public function onSearch()
    {
        $this->page['results'] = $this->results = $this->search(post('keywords'))->chunk(3);
        $this->redirectPage = $this->property('redirectPage');
    }
}