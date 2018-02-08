<?php namespace Elipce\BiPage\Components;

use Cms\Classes\ComponentBase;
use Elipce\BiPage\Models\Bookmark;
use Elipce\BiPage\Models\Page as BiPage;
use Cms\Classes\Page as CmsPage;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Exception\ApplicationException;

/**
 * Class Page
 * @package Elipce\BiPage\Components
 */
class Page extends ComponentBase
{
    /**
     * Page attributes
     * @var
     */
    public $dashboards;
    public $bipage;
    public $bookmark;

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.bipage::lang.components.page',
            'description' => 'elipce.bipage::lang.components.page_description'
        ];
    }

    /**
     * Component properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'id' => [
                'title' => 'elipce.bipage::lang.components.id',
                'description' => 'elipce.bipage::lang.components.id_description',
                'default' => '{{ :id }}',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'elipce.bipage::lang.components.id_validation'
            ],
            'redirect' => [
                'title' => 'elipce.bipage::lang.components.redirect',
                'description' => 'elipce.bipage::lang.components.redirect_description',
                'type' => 'dropdown',
                'required' => true,
                'default' => ''
            ]
        ];
    }

    /**
     * Populate redirect dropdown
     * @return array
     */
    public function getRedirectOptions()
    {
        return ['' => '- none -'] + CmsPage::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
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
    }

    /**
     * Prepare variables before process
     */
    public function prepareVars()
    {
        $this->redirectUrl = $this->controller->pageUrl($this->property('redirect'));
        $this->bipage = $this->page['bipage'] = BiPage::where('id', $this->property('id'))->first();
    }

    /**
     * Load bookmark data
     */
    public function loadBookmark()
    {
        return Bookmark::where('user_id', $this->page['user']->id)
            ->where('portal_id', $this->page['portal']->id)
            ->where('page_id', $this->page['bipage']->id)->first();
    }

    /**
     * Function called when page is loaded
     * Component's variables will be processed
     *
     * Fetch dashboards based on URL page id
     * If user is not allowed to see the page, he's redirected
     */
    public function onRun()
    {
        // Inject CSS/JS and prepare variables
        $this->addCss('assets/css/page.css');
        $this->addJs('assets/js/page.js');
        $this->validate();
        $this->prepareVars();

        // Page not found
        if ($this->bipage === null) {
            return Redirect::to($this->redirectUrl);
        }

        // User has not access to this page
        if (!$this->bipage->canAccess($this->page['user'])) {
            return Redirect::to($this->redirectUrl);
        }

        // Set page title
        $this->page->title = $this->bipage->name;

        // Inject dashboards in page
        $this->dashboards = $this->page['dashboards'] = $this->bipage->ordered_dashboards;

        // Inject subscriber button data
        if ($this->page['user'] !== null) {
            $this->bookmark = $this->loadBookmark();
            // Create empty bookmark
            if (count($this->bookmark) === 0) {
                $this->bookmark = new Bookmark();
                $this->bookmark->user_id = $this->page['user']->id;
                $this->bookmark->page_id = $this->bipage->id;
                $this->bookmark->portal_id = $this->page['portal']->id;
            }
        }

        return null;
    }

    /**
     * AJAX event : subscribe button is clicked
     */
    public function onSubscribe()
    {
        // Create bookmark
        $this->bookmark = Bookmark::create([
            'page_id' => post('page_id'),
            'user_id' => post('user_id'),
            'portal_id' => post('portal_id')
        ]);
    }

    /**
     * AJAX event : unsubscribe button is clicked
     */
    public function onUnSubscribe()
    {
        // Retrieve bookmark
        $bookmark = Bookmark::find(post('bookmark_id'));
        // Reset bookmark
        if ($bookmark->delete()) {
            // Create new empty bookmark
            $this->bookmark = new Bookmark();
            $this->bookmark->user_id = $bookmark->user_id;
            $this->bookmark->page_id = $bookmark->page_id;
            $this->bookmark->portal_id = $bookmark->portal_id;
        }
    }
}