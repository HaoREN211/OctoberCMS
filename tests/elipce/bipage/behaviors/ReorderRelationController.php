<?php namespace Elipce\BiPage\Behaviors;

use October\Rain\Extension\ExtensionBase;
use Backend\Classes\WidgetBase;
use October\Rain\Database\Model ;
use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use Backend\Models\User;

/**
 * Class SortingRelationController
 * @package Elipce\BiPage\Behaviors
 */
class ReorderRelationController extends ExtensionBase
{

    use \System\Traits\ConfigMaker;

    /**
     * Reference to the extended object.
     * @var Controller
     */
    protected $controller;

    /**
     * Reference to the backend user.
     * @var User
     */
    protected $user;

    /**
     * Configuration, keys for alias and value for config objects.
     * @var array
     */
    protected $config;

    /**
     * Configuration values that must exist when applying the primary config file.
     * @var array
     */
    protected $requiredConfig = ['modelClass', 'modelRelation'];

    /**
     * Constructor
     * @var Controller $controller
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->user = BackendAuth::getUser();

        /*
         * Load configuration
         */
        $definition = $this->controller->reorderRelationConfig;
        $this->config = $this->makeConfig($definition, $this->requiredConfig);

        /*
         * Grab the drag and drop requirements on update context
         */
        $this->controller->bindEvent('page.beforeDisplay', function ($action, $params) {
            if ($action === 'update') {
                $this->controller->addCss('/plugins/elipce/bipage/assets/css/sortable.css');
                $this->controller->addJs('/plugins/elipce/bipage/assets/js/html5sortable.js');
                $this->controller->addJs('/plugins/elipce/bipage/assets/js/sortable.js');
            }
        });
    }

    /**
     * Called before the updating form is saved.
     * @param Model
     */
    public function formBeforeUpdate($model)
    {
        $moved = [];
        $position = 1;

        /*
         * Dynamic model class name
         */
        $modelClass = $this->config->modelClass;

        if (($reorderIds = post('checked')) && is_array($reorderIds) && count($reorderIds)) {

            foreach ($reorderIds as $id) {
                if (key_exists($id, $moved) || !$record = $modelClass::find($id))
                    continue;

                $moved[$record->id] = ['position' => $position];
                $position++;
            }
        }

        /*
         * OneToMany / ManyToMany
         */
        if (empty($this->config->pivotTable)) {
            foreach ($moved as $id => $data)
                $modelClass::find($id)->update($data);
        } else {
            $model->{$this->config->modelRelation}()->sync($moved, false);
        }

        /*
         * Call original FormController behavior method
         */
        $this->controller->asExtension('FormController')->formBeforeUpdate($model);
    }

    /**
     * Provides an opportunity to manipulate the view widget.
     *
     * @param WidgetBase $widget
     * @param string $field
     * @param Model $model
     */
    public function relationExtendViewWidget($widget, $field, $model)
    {
        if ($this->controller->formGetContext() === 'update') {

            $config = $this->config;

            /*
             * Order view list
             */
            $widget->bindEvent('list.extendQueryBefore', function ($query) use ($config) {
                if (empty($config->pivotTable)) {
                    $query->orderBy('position', 'asc');
                } else {
                    $query->orderBy("{$config->pivotTable}.position", 'asc');
                }
            });

            /*
             * Add hidden ID column
             */
            $widget->addColumns([
                'id' => [
                    'cssClass' => 'position',
                    'type' => 'number'
                ]
            ]);
        }

        /*
         * Call original RelationController behavior method
         */
        $this->controller->asExtension('RelationController')->relationExtendViewWidget($widget, $field, $model);
    }

}