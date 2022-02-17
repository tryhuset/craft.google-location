<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * Select location in google map
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\services\Gql;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterGqlQueriesEvent;
use craft\events\RegisterGqlTypesEvent;
use GraphQL\Type\Definition\Type;

use yii\base\Event;

use apt\craftgooglelocation\services\Country as CountryService;
use apt\craftgooglelocation\services\Content as ContentService;
use apt\craftgooglelocation\models\Settings;
use apt\craftgooglelocation\fields\Location as LocationField;
use apt\craftgooglelocation\fields\Area as AreaField;
use apt\craftgooglelocation\integrations\feedme\Location as FeedMeLocation;
use apt\craftgooglelocation\gql\arguments\SettingsArguments;
use apt\craftgooglelocation\gql\interfaces\SettingsInterface;
use apt\craftgooglelocation\gql\interfaces\LocationInterface;
use apt\craftgooglelocation\gql\interfaces\CountryInterface;
use apt\craftgooglelocation\gql\resolvers\SettingsResolver;
use apt\craftcountriesmodule\CraftCountriesModule;

/**
 * Class CraftGoogleLocation
 *
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 *
 * @property  CountryService $country
 * @property  ContentService $content
 */
class CraftGoogleLocation extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CraftGoogleLocation
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.1.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        CraftCountriesModule::register();
        self::$plugin = $this;

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = LocationField::class;
                $event->types[] = AreaField::class;
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        if (class_exists(\craft\feedme\Plugin::class))
		{
			Event::on(
				\craft\feedme\services\Fields::class,
				\craft\feedme\services\Fields::EVENT_REGISTER_FEED_ME_FIELDS,
				[$this, 'onRegisterFeedMeFields']
			);
		}

        $this->registerGraphQL();

        Craft::info(
            Craft::t(
                'google-location',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public function onRegisterFeedMeFields(\craft\feedme\events\RegisterFeedMeFieldsEvent $event)
	{
		$event->fields[] = FeedMeLocation::class;
	}

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'google-location/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    // Private Methods
    // =========================================================================

    /**
     * Register GraphQL event listeners
     */
    private function registerGraphQL(): void
    {
        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_TYPES,
            function (RegisterGqlTypesEvent $event) {
                $event->types[] = SettingsInterface::class;
                $event->types[] = CountryInterface::class;
                $event->types[] = LocationInterface::class;
            }
        );

        Event::on(
            Gql::class,
            Gql::EVENT_REGISTER_GQL_QUERIES,
            function (RegisterGqlQueriesEvent $event) {
                $event->queries['googleLocationSettings'] =
                [
                    'type' => SettingsInterface::getType(),
                    'args' => SettingsArguments::getArguments(),
                    'resolve' => SettingsResolver::class . '::resolve',
                ];
            }
        );

    }

}
