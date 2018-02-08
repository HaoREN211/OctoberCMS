<?php namespace Elipce\BiPage\Controllers;

use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Builder;

/**
 * Collections Back-end Controller
 */
class Collections extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    /**
     * @var string Configuration files
     */
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    /**
     * @var string Body class (sidebar menu)
     */
    public $bodyClass = 'compact-container';

    /**
     * http://localhost/portail_eds/backend/elipce/multisite/portals/update/1
     * @var array Required permissions
     */
    public $requiredPermissions = [
        'elipce.bipage.access_collections',
        'elipce.bipage.preview_collections'
    ];

    /**
     * Collections constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'bipage', 'collections');
        /*
         * Prevent unauthorized backend user
         * to access create form
         */
        $this->bindEvent('page.beforeDisplay', function($action, $params) {
            if ($action === 'create') {
                array_pop($this->requiredPermissions);
            }
        });
    }

    /**
     * Overriding update controller action.
     */
    public function update($recordId, $context = null)
    {
        /*
         * Call the SortingRelationController behavior update() method
         */
        $this->asExtension('FormController')->update($recordId, $context);
        /*
         * Enable preview mode for restricted backend users
         */
        $this->formGetWidget()->previewMode = ! $this->user->hasAccess('elipce.bipage.access_collections');
    }

    /**
     * Extend the query used for populating the list
     * after the default query is processed.
     *
     * @param Builder $query
     */
    public function listExtendQuery($query)
    {
        if (! $this->user->isSuperUser()) {
            $query->whereIn('id', $this->user->collections);
        }
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
        $query->isAllowed($this->user, false);
    }

    /**
     * Ajax handler for updating the form.
     *
     * @param int $recordId The model primary key to update.
     *
     * @return mixed
     */
    public function update_onSave($recordId = null, $context = null)
    {
        $redirect = $this->asExtension('FormController')
            ->update_onSave($recordId, $context);

        if (! empty($redirect)) {
            return $redirect;
        }

        $model = $this->formGetModel();

        /*
         * Refresh relation manager
         */
        $this->initForm($model);
        $this->initRelation($model, 'dashboards');

        return $this->relationRefresh('dashboards');
    }

    /**
     * The view widget is often refreshed when the manage widget makes a change,
     * you can use this method to inject additional containers when this process
     * occurs. Return an array with the extra values to send to the browser, eg:
     *
     * return ['#myCounter' => 'Total records: 6'];
     *
     * @param string $field
     *
     * @return array
     */
    public function relationExtendRefreshResults($field)
    {
        return [
            '#Form-field-Collection-statistics-group' =>
                $this->makePartial('statistics_field', $this->vars, false)
        ];
    }
}