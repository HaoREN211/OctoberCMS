<?php namespace Elipce\BiPage\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page as CmsPage;
use Elipce\BiPage\Models\Page as PageModel;
use Illuminate\Support\Facades\Lang;
use October\Rain\Exception\ApplicationException;

/**
 * Class Focus
 * @package Elipce\BiPage\Components
 */
class Focus extends ComponentBase
{
    /**
     * Component attributes
     * @var
     */
    public $pages;
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
            'name' => 'elipce.bipage::lang.components.focus',
            'description' => 'elipce.bipage::lang.components.focus_description'
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
                'title' => 'elipce.bipage::lang.components.redirect_page',
                'description' => 'elipce.bipage::lang.components.redirect_page_description',
                'type' => 'dropdown',
                'default' => 'page'
            ],
            'pagination' => [
                'title' => 'elipce.bipage::lang.components.pagination',
                'description' => 'elipce.bipage::lang.components.pagination_description',
                'default' => '5',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'elipce.bipage::lang.components.pagination_validation'
            ],
            'sorting' => [
                'title' => 'elipce.bipage::lang.components.sorting',
                'description' => 'elipce.bipage::lang.components.sorting_description',
                'type' => 'dropdown',
                'default' => 'desc'
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
            'asc' => Lang::get('elipce.bipage::lang.components.sorting_asc'),
            'desc' => Lang::get('elipce.bipage::lang.components.sorting_desc')
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
        $this->redirectPage = $this->property('redirectPage');
        $this->pagination = $this->property('pagination');
        $this->sorting = $this->property('sorting');
    }

    /**
     * Load focused pages for current portal
     * @return mixed
     */
    public function loadFocus()
    {
        // Query
        $pages = $this->page['portal']
            ->focus()
            ->orderBy('updated_at', $this->sorting)
            ->limit($this->pagination)
            ->get();

        // Only allowed focused pages
        return $pages->filter(function ($page) {
            return $page->canAccess($this->page['user']);
        });
    }

    /**
     * Load default last pages for current portal
     * @return mixed
     */
    public function loadPages()
    {
        // Copy portal ID from another component
        $portal =  $this->page['portal']->id;

        // Build query
        $query = PageModel::applyPortal($portal)
            ->orderBy('updated_at', $this->sorting)
            ->limit($this->pagination);

        // Lazy loading
        $query->with(['folders' => function ($query) use ($portal) {
            $query->where('portal_id', $portal);
        }]);

        // Execute query
        $pages = $query->get();

        // Only allowed pages
        return $pages->filter(function ($page) {
            return $page->canAccess($this->page['user']);
        });
    }

    /**
     * On component run
     */
    public function onRun()
    {
        // Inject assets
        $this->addCss('assets/css/slide.css');
        $this->addCss('assets/vendor/slick/slick.css');
        $this->addJs('assets/js/focus.js');
        $this->addJs('assets/vendor/slick/slick.min.js');

        // Prepare data
        $this->validate();
        $this->prepareVars();

        // Load data
        $this->pages = $this->loadFocus();

        // No focus => last allowed pages
        if (count($this->pages) === 0) {
            $this->pages = $this->loadPages();
        }
    }
}