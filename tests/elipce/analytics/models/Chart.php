<?php namespace Elipce\Analytics\Models;

use Elipce\Analytics\Classes\AnalyticsService;
use Elipce\BiPage\Contracts\Visualization;
use Elipce\BiPage\Models\Page;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use RainLab\User\Models\User;
use Carbon\Carbon;

/**
 * Model Chart
 */
class Chart extends Model implements Visualization
{

    /**
     * Traits
     */
    use \Elipce\BiPage\Traits\Visualization;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'       => 'required',
        'view'       => 'required',
        'start_date' => 'before:end_date',
        'metric'     => 'required|exists:elipce_analytics_metadata,code',
        'dimension'  => 'exists:elipce_analytics_metadata,code'
    ];

    /**
     * @var array Attribute names for validation errors.
     */
    public $attributeNames = [
        'name'       => 'elipce.analytics::lang.backend.charts.name_label',
        'view'       => 'elipce.analytics::lang.backend.charts.view_label',
        'start_date' => 'elipce.analytics::lang.backend.charts.start_date_label',
        'end_date'   => 'elipce.analytics::lang.backend.charts.end_date_label',
        'metric'     => 'elipce.analytics::lang.backend.charts.metric_label',
        'dimension'  => 'elipce.analytics::lang.backend.charts.dimension_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_analytics_charts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type',
        'metric',
        'dimension',
        'start_date',
        'end_date'
    ];

    /**
     * @var array Date fields
     */
    protected $dates = ['start_date', 'end_date'];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'view' => ['Elipce\Analytics\Models\View', 'key' => 'view_id']
    ];

    /**
     * Before the model is saved.
     */
    public function beforeSave()
    {
        /*
         * Default end date
         */
        if (empty($this->end_date)) {
            $this->end_date = Carbon::now();
        }
        /*
         * Default start date
         */
        if (empty($this->start_date)) {
            $this->start_date = $this->end_date->subMonth(1);
        }
        /*
         * Default dimension
         */
        if (empty($this->dimension)) {
            $this->dimension = 'ga:date';
        }
    }

    /**
     * Scope a query to only include charts of a given property.
     *
     * @param Builder $query
     * @param int $propertyId
     * @return Builder
     */
    public function scopeApplyProperty($query, $propertyId)
    {
        return $query->join('elipce_analytics_views', 'elipce_analytics_charts.view_id', '=',
            'elipce_analytics_views.id')
            ->where('elipce_analytics_views.property_id', $propertyId)
            ->select('elipce_analytics_charts.*');
    }

    /**
     * Scope a query to only include charts of a given account.
     *
     * @param Builder $query
     * @param int $accountId
     * @return Builder
     */
    public function scopeApplyAccount($query, $accountId)
    {
        return $query->join('elipce_analytics_views', 'elipce_analytics_charts.view_id', '=',
            'elipce_analytics_views.id')
            ->join('elipce_analytics_properties', 'elipce_analytics_views.property_id', '=',
                'elipce_analytics_properties.id')
            ->where('elipce_analytics_properties.account_id', $accountId)
            ->select('elipce_analytics_charts.*');
    }

    /**
     * Get view options.
     *
     * @return array
     */
    public function getViewOptions()
    {
        return View::all()->lists('fullname', 'id');
    }

    /**
     * Get metric options.
     *
     * @return array
     */
    public function getMetricOptions()
    {
        return Metadata::metrics()->lists('name', 'code');
    }

    /**
     * Get dimension options.
     *
     * @return array
     */
    public function getDimensionOptions()
    {
        return Metadata::dimensions()->lists('name', 'code');
    }

    /**
     * Check if a given user can access this chart.
     *
     * @param User|null $user
     * @return bool
     */
    public function canAccess(User $user = null)
    {
        return ! empty($user);
    }

    /**
     * Build dashboard partial parameters.
     *
     * @param Page $page
     * @param User|null $user
     * @return string
     */
    public function buildPartialParameters(Page $page, User $user = null)
    {
        $analyticsService = new AnalyticsService($this->view->property->account);

        return [
            '~/plugins/elipce/analytics/partials/_chart.htm',
            [
                'id'        => $this->id,
                'token'     => $analyticsService->generateToken(),
                'viewId'    => $this->view->external_id,
                'metric'    => $this->metric,
                'dimension' => $this->dimension,
                'type'      => $this->type,
                'startDate' => $this->start_date->toDateString(),
                'endDate'   => $this->end_date->toDateString()
            ]
        ];
    }

    /**
     * Get dashboard title.
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->name;
    }

    /**
     * Get dashboard type.
     *
     * @return string
     */
    public function getType()
    {
        return "Google Analytics";
    }
}