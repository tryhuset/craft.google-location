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
use apt\craftgooglelocation\assetbundles\areafield\AreaSettingsAsset;
use apt\craftgooglelocation\assetbundles\areafield\AreaFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use craft\elements\Entry;
use yii\db\Schema;
use craft\helpers\Json;

use apt\craftgooglelocation\models\Location as Model;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class Area extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var boolean
     */
    public $markers = false;

    /**
     * @var string
     */
    public $section = '';

    /**
     * @var string
     */
    public $type = '';

    /**
     * @var string
     */
    public $field = '';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('craft-google-location', 'Google map area');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['markers', 'boolean'],
            [['section', 'type', 'field'], 'string'],
            [['section', 'type', 'field'], 'default', 'value' => null],
            ['markers', 'default', 'value' => false],
        ]);

        if ($this->markers) {
            $rules = array_merge($rules, [
                [['section', 'type', 'field'], 'required'],
            ]);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if (empty($value)) {
            return [];
        }

        if (is_string($value)) {
            $json = json_decode($value, true);
            if ($json) {
                return $json;
            }
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        Craft::$app->getView()->registerAssetBundle(AreaSettingsAsset::class);
        $locationFields = CraftGoogleLocation::getInstance()->content->getLocationsFields();
        d($locationFields);
        $sections = [[
            'value' => '',
            'label' => Craft::t('craft-google-location', 'Select section'),
        ]];
        $jssections = [];
        $types = [];
        $fields = [];

        $prefix = Craft::$app->getView()->namespaceInputId('');
        $jsonVars = [
            'prefix' => $prefix,
            'markers' => $this->markers,
            'sections' => $jssections,
            'section' => $this->section,
            'type' => $this->type,
            'field' => $this->field,
        ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$prefix}field').CraftGoogleLocationAreaSettings(" . $jsonVars . ");");

        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'craft-google-location/_components/fields/Area_settings',
            [
                'field' => $this,
                'sections' => $sections,
                'types' => $types,
                'fields' => $fields,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $markers = [];
        if ($this->markers) {
            $entries = Entry::find()->section($this->section)->type($this->type)->all();
            foreach ($entries as $entry) {
                $location = $entry[$this->field];
                if ($location instanceof Model) {
                    $markers[] = [
                        'title' => $entry->title,
                        'position' => [
                            'lat' => floatval($location->lat),
                            'lng' => floatval($location->lng),
                        ],
                    ];
                }
            }
        }

        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(AreaFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            'markers' => $markers,
            'shapes' => $value,
        ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').CraftGoogleLocationArea(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'craft-google-location/_components/fields/Area_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
