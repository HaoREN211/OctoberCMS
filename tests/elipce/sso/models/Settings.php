<?php namespace Elipce\SSO\Models;

use Elipce\SSO\Facades\SingleSignOnHelper;
use October\Rain\Database\Model;

/**
 * Settings Back-end Controller
 */
class Settings extends Model
{

    /**
     * Default mapping attributes
     */
    const EMAIL_ATTRIBUTE = 'mail';
    const FIRSTNAME_ATTRIBUTE = 'fn';
    const LASTNAME_ATTRIBUTE = 'sn';

    /**
     * @var array Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string Unique code
     */
    public $settingsCode = 'saml_settings';

    /**
     * @var string Reference to field configuration.
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Initialize values for mapping attributes.
     */
    public function initSettingsData()
    {
        $this->emailAttribute = self::EMAIL_ATTRIBUTE;
        $this->firstnameAttribute = self::FIRSTNAME_ATTRIBUTE;
        $this->lastnameAttribute = self::LASTNAME_ATTRIBUTE;

        $this->debug = false;
        $this->selfregister = false;
    }

    /**
     * Mutator for service provider entity id.
     * @return string
     */
    public function getSPIDAttribute()
    {
        return  SingleSignOnHelper::getSettings('sp.entityId');
    }

    /**
     * Mutator for service provider acs url.
     * @return string
     */
    public function getSPACSAttribute()
    {
        return SingleSignOnHelper::getSettings('sp.assertionConsumerService.url');
    }

    /**
     * Mutator for service provider acs url.
     * @return string
     */
    public function getSPSLSAttribute()
    {
        return SingleSignOnHelper::getSettings('sp.singleLogoutService.url');
    }
}