<?php namespace Elipce\Multisite\Models;

use October\Rain\Exception\ApplicationException;
use October\Rain\Support\Facades\Config;
use Backend\Models\User as BackendUser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use October\Rain\Database\Builder;
use October\Rain\Database\Model;
use Cms\Classes\Theme;

/**
 * Model Portal
 */
class Portal extends Model
{

    /**
     * Traits
     */
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Purgeable;

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'theme' => 'required',
        'domain' => 'required',
        'host' => 'unique:elipce_multisite_portals, "host"'
    ];

    /**
     * @var array Attribute names for validation errors
     */
    public $attributeNames = [
        'name' => 'elipce.multisite::lang.backend.portals.name_label',
        'theme' => 'elipce.multisite::lang.backend.portals.theme_label',
        'domain' => 'elipce.multisite::lang.backend.portals.domain_label',
        'host' => 'elipce.multisite::lang.backend.portals.domain_label',
        'favicon' => 'elipce.multisite::lang.backend.portals.favicon_label',
        'loginlogo' => 'elipce.multisite::lang.backend.portals.loginlogo_label',
        'navbarlogo' => 'elipce.multisite::lang.backend.portals.navbarlogo_label'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'elipce_multisite_portals';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'description',
        'domain',
        'subdomain',
        'host',
        'theme'
    ];

    /**
     * @var array List of attributes to purge.
     */
    protected $purgeable = ['less'];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'backend_users' => ['Backend\Models\User', 'key' => 'portal_id']
    ];
    public $attachOne = [
        'navbarlogo' => 'System\Models\File',
        'favicon' => 'System\Models\File',
        'loginlogo' => 'System\Models\File'
    ];

    /**
     * Scope a query to only include allowed portals
     * for a given backend user.
     *
     * @param Builder $query
     * @param BackendUser $user
     * @return Builder
     */
    public function scopeIsAllowed($query, BackendUser $user)
    {
        if ($user->isSuperUser()) {
            return $query;
        }

        return $query->where('id', $user->portal->id);
    }

    /**
     * Before the model is saved.
     * (build host URL)
     */
    public function beforeSave()
    {
        /*
         * Build the host URL
         */
        $this->host = $this->subdomain ?
            "{$this->subdomain}.{$this->domain}" : $this->domain;
    }

    /**
     * After the model is saved.
     * (update cache after saving new domain)
     */
    public function afterSave()
    {
        Cache::forget('elipce_multisite_portals');
        $cache = self::generateCacheRecords();
        Cache::forever('elipce_multisite_portals', $cache);
    }

    /**
     * Get theme options.
     *
     * @return array
     */
    public function getThemeOptions()
    {
        $options = [];

        foreach (Theme::all() as $theme) {
            $options[$theme->getDirName()] =
                $theme->getConfigValue('name');
        }

        return $options;
    }

    /**
     * Mutator for theme name attribute.
     *
     * @return string
     */
    public function getThemeNameAttribute()
    {
        return Theme::load($this->theme)->getConfigValue('name');
    }

    /**
     * Mutator for less attribute.
     *
     * @return string
     * @throws ApplicationException
     */
    public function getLessAttribute()
    {
        /*
         * Build file path
         */
        $filename = themes_path() . '/' . $this->theme .
            '/assets/vendor/flat-ui/less/variables.less';
        /*
         * Get default portal's theme variables less file
         */
        try {
            return File::get($filename);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Mutator for full url.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        $url = explode('/', Config::get('app.url'));
        $url[2] = $this->host;
        return implode('/', $url);
    }

    /**
     * Generate cache records for domains.
     *
     * @return array cache records
     */
    private static function generateCacheRecords()
    {
        $portals = self::all();
        $cache = [];

        foreach ($portals as $portal) {
            $cache[] = [
                'id' => $portal->id,
                'host' => $portal->host,
            ];
        }

        return $cache;
    }

    /**
     * Get cache domain records, generate it if not exist.
     *
     * @return array
     */
    public static function getCache()
    {
        $portals = Cache::rememberForever('elipce_multisite_portals', function () {
            try {
                $cache = self::generateCacheRecords();
            } catch (\Illuminate\Database\QueryException $e) {
                return null;
            }
            return $cache;
        });

        return $portals;
    }
}