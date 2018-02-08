<?php namespace Elipce\BiPage\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page as CmsPage;
use October\Rain\Exception\ApplicationException;

/**
 * Class Folders
 * @package Elipce\BiPage\Components
 */
class Folders extends ComponentBase
{
    /**
     * Component attributes
     * @var
     */
    public $folders;
    public $redirectPage;

    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'elipce.bipage::lang.components.folders',
            'description' => 'elipce.bipage::lang.components.folders_description'
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
    }

    /**
     * Load current domain's folders with pages
     * @return array
     */
    public function loadFolders()
    {
        $folders = [];

        $this->page['portal']->folders->each(function ($folder) use (&$folders) {
            if ($folder->canAccess($this->page['user'])) {
                $data = [
                    'id' => $folder->id,
                    'title' => $folder->name,
                    'pages' => $folder->ordered_pages->filter(function ($page) {
                        return $page->canAccess($this->page['user']);
                    })
                ];

                $folders[] = $data;
            }
        });

        return $folders;
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
        $this->addCss('assets/css/folders.css');

        // Inject data
        $this->folders = $this->page['folders'] = $this->loadFolders();
    }

}