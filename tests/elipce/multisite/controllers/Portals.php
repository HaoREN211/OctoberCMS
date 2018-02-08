<?php namespace Elipce\Multisite\Controllers;

use October\Rain\Exception\ValidationException;
use Illuminate\Support\Facades\Artisan;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use Backend\Classes\WidgetBase;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;

/**
 * Portals Back-end Controller
 */
class Portals extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Elipce.Multisite.Behaviors.RedirectionController'
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
    public $relationConfig = null;
    public $reorderRelationConfig = null;

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.multisite.*'];

    /**
     * Portals constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.Multisite', 'multisite', 'portals');
    }

    /**
     * Overriding create form controller action.
     *
     * @param null $context
     * @return mixed
     */
    public function create($context = null)
    {
        /*
         * Only super users
         */
        if (!$this->user->isSuperUser()) {
            return $this->makeRedirect();
        }
        /*
         * Call the FormController behavior update() method
         */
        return $this->asExtension('FormController')->create($context);
    }

    /**
     * List AJAX handler to clear cache.
     */
    public function index_onClearCache()
    {
        Cache::forget('elipce_multisite_portals');
        Flash::success(Lang::get('elipce.multisite::lang.backend.portals.cache_cleared'));
    }

    /**
     * Called after the form fields are defined.
     * @param WidgetBase $widget
     */
    public function formExtendFields($widget)
    {
        /*
         * Remove backend fields for non super users
         */
        if (!$this->user->isSuperUser()) {
            $widget->removeField('domain');
            $widget->removeField('subdomain');
            $widget->removeField('theme');
            $widget->removeField('less');
        }
    }

    /**
     * Called before the updating form is saved.
     *
     * @param Model $model
     * @throws ValidationException
     */
    public function formAfterUpdate($model)
    {
        /*
         * Build path
         */
        $filename = themes_path() . '/' . $model->theme .
            '/assets/vendor/flat-ui/less/variables.less';
        /*
         * Get less content from text field
         */
        $less = input('Portal[less]');
        /*
         * No file
         */
        if (empty($less)) return;

        try {
            /*
             * Only when there is modification
             */
            if (File::get($filename) != $less) {
                /*
                 * Overwrite LESS variables
                 */
                File::put($filename, input('Portal[less]'));
                Artisan::call('cache:clear');
                Artisan::call('october:util', ['name' => 'compile', 'name' => 'less']);
            }

        } catch (\Exception $e) {

            throw new ValidationException([Lang::get('elipce.multisite::lang.backend.portals.less_error')]);
        }
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
     * @param October\Rain\Database\Builder $query
     * @return void
     */
    public function formExtendQuery($query)
    {
        $query->isAllowed($this->user);
    }
}