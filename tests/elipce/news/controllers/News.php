<?php namespace Elipce\News\Controllers;

use Backend\Classes\FilterScope;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;

/**
 * News Back-end Controller
 */
class News extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Elipce.Multisite.Behaviors.PortalController'
    ];

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.multisite.access_news'];

    /**
     * News constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.Multisite', 'multisite', 'news');
    }

    /**
     * Extend query used for populating the relation form widget options.
     *
     * @param Builder $query
     * @param string $field
     */
    public function formRelationExtendQuery($query, $field)
    {
        if ($field === 'page') {
            $portal = $this->formGetModel()->portal->id;
            $query->applyPortal($portal)->published();
        }
    }

    /**
     * Extend the query used for populating the filter
     * options before the default query is processed.
     *
     * @param Builder $query
     * @param FilterScope $scope
     */
    public function listFilterExtendQuery($query, $scope)
    {
        if ($scope->scopeName === 'portal') {
            $query->isAllowed($this->user);
        }
    }
}