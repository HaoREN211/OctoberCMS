<?php namespace Elipce\Bime\Models;

use Illuminate\Support\Facades\Lang;
use October\Rain\Database\Model;

/**
 * Parameter Model
 */
class Parameter extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;

    /**
     * Validation rules
     */
    public $rules = [
        'name' => 'required',
        'type' => 'required'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.bime::lang.backend.parameters.name_label',
        'type' => 'elipce.bime::lang.backend.parameters.type_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_bime_parameters';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * Mutator for translated type attribute.
     *
     * @return string
     */
    public function getTranslatedTypeAttribute()
    {
        $lang = [
            'string' => Lang::get('elipce.bime::lang.backend.parameters.string_option'),
            'integer' => Lang::get('elipce.bime::lang.backend.parameters.integer_option'),
            'date' => Lang::get('elipce.bime::lang.backend.parameters.date_option')
        ];
        return $lang[$this->type];
    }
}