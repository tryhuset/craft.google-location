<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * Select location in google map
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation\models;

use Craft;
use craft\base\Model;
use apt\craftgooglelocation\CraftGoogleLocation;
use apt\craftcountriesmodule\CraftCountriesModule;
use apt\craftcountriesmodule\models\Country;


const DEFAULT_COUNTRY = 'NO';
const DEFAULT_ZOOM = 10;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class Location extends Model
{
    protected $countryService;

    /**
     * @var string
     */
    public $country = null;

    /**
     * @var string
     */
    public $address = '';

    /**
     * @var float
     */
    public $lat = null;

    /**
     * @var float
     */
    public $lng = null;

    /**
     * @var integer
     */
    public $zoom = DEFAULT_ZOOM;

    // Public Methods
    // =========================================================================

    public function __construct($config = [])
    {
        if (array_key_exists('position', $config)) {
            $config['lat'] = $config['position']['lat'];
            $config['lng'] = $config['position']['lng'];
            unset($config['position']);
        }
        parent::__construct($config);
        CraftCountriesModule::register();
        $this->countryService = Craft::$app->getModule('countries')->service;

        if (empty($this->country)) {
            $this->country = $this->countryService->getCountryByCode(DEFAULT_COUNTRY);
        } else {
            if (!$this->country instanceof Country) {
                $this->country = $this->countryService->getCountryByCode($this->country);
            }
        }
    }

    public function validateCountry()
    {
        if (!$this->country instanceof Country) {
            $this->addError('country', Craft::t('site', 'Invalid country'));
        }
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $defaultCountry = $this->countryService->getCountryByCode(DEFAULT_COUNTRY);
        return [
            [['lat', 'lng', 'zoom'], 'number'],
            ['address', 'string'],
            ['country', 'validateCountry'],
            ['country', 'default', 'value' => $defaultCountry],
            ['address', 'default', 'value' => ''],
            ['lat', 'default', 'value' => null],
            ['lng', 'default', 'value' => null],
            ['zoom', 'default', 'value' => DEFAULT_ZOOM],
        ];
    }
}
