<?php namespace Elipce\Comments\Controllers;

use October\Rain\Database\Builder;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Lang;
use elipce\Comments\Models\Comment;
use Backend\Facades\BackendMenu;
use Backend\Classes\Controller;
use Backend\Classes\FilterScope;
use Backend;

/**
 * Comments Back-end Controller
 */
class Comments extends Controller
{

    /**
     * @var array Behaviors
     */
    public $implement = [
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string List configuration file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['elipce.bipage.access_comments'];

    /**
     * Comments constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Elipce.BiPage', 'bipage', 'comments');
    }

    /**
     * List AJAX handler to hide selected comments.
     *
     * @return mixed
     */
    public function index_onHide()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            Comment::unpublish($checkedIds);
            Flash::success(Lang::get('backend::lang.form.update_success'));
        }

        return $this->listRefresh();
    }

    /**
     * List AJAX handler to show selected comments.
     *
     * @return mixed
     */
    public function index_onShow()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            Comment::publish($checkedIds);
            Flash::success(Lang::get('backend::lang.form.update_success'));
        }

        return $this->listRefresh();
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
        $query->isAllowed($this->user);
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
}