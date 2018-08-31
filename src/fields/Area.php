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

    /**
     * @var string
     */
    public $fieldType = 'location';

    /**
     * @var string
     */
    public $matrixBlock = '';

    /**
     * @var string
     */
    public $matrixBlockField = '';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('craft-google-location', 'Google map area');
    }

    public function getAttributeLabel($attribute): string
    {
        $label = parent::getAttributeLabel($attribute);

        return Craft::t('craft-google-location', $label);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['markers', 'boolean'],
            [['section', 'type', 'field', 'fieldType', 'matrixBlock', 'matrixBlockField'], 'string'],
            [['section', 'type', 'field', 'fieldType', 'matrixBlock', 'matrixBlockField'], 'default', 'value' => null],
            ['markers', 'default', 'value' => false],
        ]);

        if ($this->markers) {
            $rules = array_merge($rules, [
                [['section', 'type', 'field', 'fieldType'], 'required', 'message' => Craft::t('craft-google-location', '{attribute} cannot be blank.')],
            ]);
        }

        if ($this->fieldType === 'matrix') {
            $rules = array_merge($rules, [
                [['matrixBlock', 'matrixBlockField'], 'required', 'message' => Craft::t('craft-google-location', '{attribute} cannot be blank.')],
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
        $contentService = CraftGoogleLocation::getInstance()->content;

        $prefix = Craft::$app->getView()->namespaceInputId('');
        $jsonVars = [
            'prefix' => $prefix,
            'sections' => $contentService->getOptions(),
            'state' => [
                'markers' => (!!$this->markers),
                'section' => $this->section,
                'type' => $this->type,
                'field' => $this->field,
                'matrixBlock' => $this->matrixBlock,
                'matrixBlockField' => $this->matrixBlockField,
            ],
        ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$prefix}field').CraftGoogleLocationAreaSettings(" . $jsonVars . ");");

        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'craft-google-location/_components/fields/Area_settings',
            [
                'field' => $this,
                'sections' => $contentService->getSectionOptions(),
                'types' => $contentService->getTypeOptions($this->section),
                'fields' => $contentService->getFieldOptions($this->section, $this->type),
                'matrixBlocks' => $contentService->getMatrixBlocksOptions($this->section, $this->type, $this->field),
                'matrixBlockFields' => $contentService->getMatrixBlockFieldOptions($this->section, $this->type, $this->field, $this->matrixBlock),
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
            $markers = $contentService = CraftGoogleLocation::getInstance()->content->getLocations($this->getSettings());
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
