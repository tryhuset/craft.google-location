<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * Select location in google map
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation\fields;

use apt\craftgooglelocation\CraftGoogleLocation;
use apt\craftgooglelocation\assetbundles\locationfield\LocationFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

use apt\craftgooglelocation\models\Location as Model;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class Location extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $country = null;

    /**
     * @var boolean
     */
    public $address = false;

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('craft-google-location', 'Google map address location');
    }

    // Public Methods
    // =========================================================================

    public function __construct($config = [])
    {
        /* transform old address value to new */
        if (array_key_exists('address', $config)) {
            $config['address'] = (bool)$config['address'];
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['country', 'string'],
            ['address', 'boolean'],
            ['country', 'default', 'value' => null],
            ['address', 'default', 'value' => false],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if (empty($value)) {
            return new Model();
        }

        if (is_array($value)) {
            return new Model($value);
        }

        if (is_string($value)) {
            $json = json_decode($value, true);
            if ($json) {
                return new Model($json);
            }
        }

        return new Model();
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        dd(CraftGoogleLocation::getInstance());
        $countries = CraftGoogleLocation::getInstance()->country->getCountries();
        array_unshift($countries, [
            'value' => null,
            'label' => Craft::t('craft-google-location', 'All countries'),
        ]);
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'craft-google-location/_components/fields/Location_settings',
            [
                'field' => $this,
                'countries' => $countries,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // $countries = CraftGoogleLocation::getInstance()->country->getCountries();
        dd(CraftGoogleLocation::getInstance());

        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(LocationFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        if ($this->country) {
            $value['country'] = $this->country;
        }

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            'country' => $this->country,
            'address' => $this->address,
        ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').CraftGoogleLocationLocation(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'craft-google-location/_components/fields/Location_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
                'countries' => $countries,
                'showCountries' => (!$this->country),
                'showAddress' => $this->address,
            ]
        );
    }
}
