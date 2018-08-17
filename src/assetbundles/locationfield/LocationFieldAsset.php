<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * Select location in google map
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation\assetbundles\locationfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use apt\craftgooglelocation\CraftGoogleLocation;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class LocationFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $apiKey = CraftGoogleLocation::getInstance()->settings->apiKey;
        $this->sourcePath = "@apt/craftgooglelocation/assetbundles/locationfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Location.js',
            "https://maps.googleapis.com/maps/api/js?key={$apiKey}&callback=AptCraftGoogleLocationInitMap",
        ];

        $this->css = [
            'css/Location.css',
        ];

        parent::init();
    }
}
