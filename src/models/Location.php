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
use apt\craftcountries\CraftCountries;
use apt\craftcountries\models\Country;


const DEFAULT_COUNTRY = 'NO';
const DEFAULT_ZOOM = 10;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class Location extends Model
{
    // Public Properties
    // =========================================================================

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
        parent::__construct($config);
        if (empty($this->country)) {
            $this->country = CraftCountries::getInstance()->service->getCountryByCode(DEFAULT_COUNTRY);
        } else {
            if (!$this->country instanceof Country) {
                $this->country = CraftCountries::getInstance()->service->getCountryByCode($this->country);
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
    public function rules()
    {
        $defaultCountry = CraftCountries::getInstance()->service->getCountryByCode(DEFAULT_COUNTRY);
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
