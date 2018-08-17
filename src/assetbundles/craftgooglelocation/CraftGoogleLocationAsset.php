<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * Select location in google map
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation\assetbundles\CraftGoogleLocation;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class CraftGoogleLocationAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@apt/craftgooglelocation/assetbundles/craftgooglelocation/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CraftGoogleLocation.js',
        ];

        $this->css = [
            'css/CraftGoogleLocation.css',
        ];

        parent::init();
    }
}
