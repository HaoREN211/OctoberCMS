<?php namespace Elipce\BiPage\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page as CmsPage;
use Elipce\BiPage\Models\Page as PageModel;
use Illuminate\Support\Facades\Lang;
use October\Rain\Exception\ApplicationException;

/**
 * Class Showcase
 * @package Elipce\BiPage\Components
 */
class Showcase extends ComponentBase
{

    /**
     * Display modes
     */
    const SLIDE_MODE = 'slide';
    const LIST_MODE = 'list';

    /**
     * Component attributes
     * @var
     */
    public $pages;
    public $redirectPage;
    public $displayMode;

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.bipage::lang.components.showcase',
            'description' => 'elipce.bipage::lang.components.showcase_description'
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
            'displayMode' => [
                'title' => 'elipce.bipage::lang.components.display_mode',
                'description' => 'elipce.bipage::lang.components.display_mode_description',
                'type' => 'dropdown',
                'default' => self::SLIDE_MODE
            ]
        ];
    }

    /**
     * Populate display mode dropdown
     * @return mixed
     */
    public function getDisplayModeOptions()
    {
        return [
            Lang::get('elipce.bipage::lang.components.slide_mode') => self::SLIDE_MODE,
            Lang::get('elipce.bipage::lang.components.list_mode') => self::LIST_MODE
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
        $this->redirectPage = $this->property('redirectPage');
        $this->displayMode = $this->property('displayMode');
    }

    /**
     * Load public pages
     * @return mixed
     */
    public function loadPublicPages()
    {
        // Query
        $pages = PageModel::applyPortal($this->page['portal']->id)
            ->published()
            ->get();

        // Only get public pages
        return $pages->filter(function ($page) {
            return $page->public;
        });
    }

    /**
     * On component run
     */
    public function onRun()
    {
        // Prepare data
        $this->validate();
        $this->prepareVars();

        // Inject assets
        if ($this->displayMode === self::SLIDE_MODE) {
            $this->addCss('assets/vendor/slick/slick.css');
            $this->addCss('assets/css/slide.css');
            $this->addJs('assets/vendor/slick/slick.min.js');
            $this->addJs('assets/js/showcase.js');
        } else {
            $this->addCss('assets/css/list.css');
        }

        // Load data
        $this->pages = $this->loadPublicPages();

        // Chunk data
        if ($this->displayMode === self::LIST_MODE) {
            $this->pages = $this->pages->chunk(3);
        }
    }
}