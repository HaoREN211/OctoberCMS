<?php namespace Elipce\BiPage\Traits;

use Elipce\BiPage\Models\Dashboard;

/**
 * Class VisualizationObserver
 * @package Elipce\BiPage\Observers
 */
class VisualizationObserver
{

    /**
     * Delete the generic visualization
     * @param $model
     * @throws \Exception
     */
    public function deleting($model)
    {
        Dashboard::findOrFail($model->id)->delete();
    }

    /**
     * Update the generic visualization
     * @param $model
     */
    public function updating($model)
    {
        // Fetch associated generic visualization
        $dashboard = Dashboard::findOrFail($model->id);

        // Update generic visualization attributes
        $dashboard->update([
            'name' => $model->getTitle(),
            'type' => $model->getType(),
            'model_class' => get_class($model),
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ]);
    }

    /**
     * Create a new generic visualization
     * @param $model
     */
    public function creating($model)
    {
        // Create generic visualization
        $dashboard = Dashboard::create([
            'name' => $model->getTitle(),
            'type' => $model->getType(),
            'model_class' => get_class($model),
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ]);
        // Attach new generic visualization
        $model->incrementing = false;
        $model->id = $dashboard->id;
    }

}