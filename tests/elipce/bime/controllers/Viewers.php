<?php namespace Elipce\Bime\Controllers;

use Backend\Classes\FilterScope;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;

/**
 * Viewers Back-end Controller
 */
class Viewers extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',

    ];

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.datasources.bime.access_viewers'];

    /**
     * Viewers constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'datasources', 'bime_viewers');
    }

    /**
     * Extend the query used for populating the list
     * after the default query is processed.
     *
     * @param Builder $query
     */
    public function listExtendQuery($query)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend the query used for finding the form model. Extra conditions
     * can be applied to the query, for example, $query->withTrashed();
     *
     * @param October\Rain\Database\Builder $query
     *
     * @return void
     */
    public function formExtendQuery($query)
    {
        $query->isAllowed($this->user);
    }

    /**
     * Extend query used for populating
     * the relation form widget options.
     *
     * @param Builder $query
     * @param string $field
     */
    public function formRelationExtendQuery($query, $field)
    {
        $account = $this->formGetModel()->account->id;
        $query->isAllowed($this->user)->applyAccount($account);
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
        if ($scope->scopeName === 'group') {
            $query->isAllowed($this->user);
        }
    }
}