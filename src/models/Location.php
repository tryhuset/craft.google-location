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
const DEFAULT_LAT = 59.8939225;
const DEFAULT_LNG = 10.7150775;

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
    public $lat = DEFAULT_LAT;

    /**
     * @var float
     */
    public $lng = DEFAULT_LNG;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lat', 'lng'], 'number'],
            ['country', 'default', 'value' => DEFAULT_COUNTRY],
            ['lat', 'default', 'value' => DEFAULT_LAT],
            ['lng', 'default', 'value' => DEFAULT_LNG],
        ];
    }
}
