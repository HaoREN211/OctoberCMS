<?php namespace Elipce\LimeSurvey\Models;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;

/**
 * Question Model
 */
class Question extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_limesurvey_questions';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'answer' => ['Elipce\LimeSurvey\Models\Question', 'key' => 'question_id'],
        'template' => ['Elipce\LimeSurvey\Models\Template', 'key' => 'template_id']
    ];

    /**
     * Mutator for full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->template->name . ' -> ' . $this->name;
    }

    /**
     * Scope a query to only include allowed questions.
     *
     * @param Builder $query
     * @param Template $template
     * @return Builder
     */
    public function scopeIsAllowed($query, $template)
    {
        /*
         * Exclude same template questions and force same collection
         */
        $templates = Template::where('collection_id', $template->collection_id)
            ->where('id', '<>', $template->id)
            ->lists('id');

        return $query->whereIn('template_id', $templates);
    }

    /**
     * Scope a query to only include linked questions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLinked($query)
    {
        return $query->whereNotNull('question_id');
    }
}