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

use apt\craftgooglelocation\CraftGoogleLocation;

use Craft;
use craft\base\Model;

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
    public $country = DEFAULT_COUNTRY;

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lat', 'lng', 'zoom'], 'number'],
            [['country', 'address'], 'string'],
            ['country', 'default', 'value' => DEFAULT_COUNTRY],
            ['address', 'default', 'value' => ''],
            ['lat', 'default', 'value' => null],
            ['lng', 'default', 'value' => null],
            ['zoom', 'default', 'value' => DEFAULT_ZOOM],
        ];
    }

    public function getCountryName()
    {
        return CraftGoogleLocation::getInstance()->country->getCountryName($this->country);
    }
}
