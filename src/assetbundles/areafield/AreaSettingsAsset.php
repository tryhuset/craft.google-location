<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * Select location in google map
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation\assetbundles\areafield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use apt\craftgooglelocation\CraftGoogleLocation;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class AreaSettingsAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@apt/craftgooglelocation/assetbundles/areafield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Settings.js',
        ];

        $this->css = [
            'css/Settings.css',
        ];

        parent::init();
    }
}
