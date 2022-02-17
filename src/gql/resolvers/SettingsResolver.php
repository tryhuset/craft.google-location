<?php
namespace apt\craftgooglelocation\gql\resolvers;

use craft\helpers\App;

use apt\craftgooglelocation\CraftGoogleLocation;

class SettingsResolver
{
    public static function resolve($root, $args)
    {
        $apiKey = CraftGoogleLocation::getInstance()->getSettings()->apiKey;

        return [
            'apiKey' => App::parseEnv($apiKey),
        ];
    }
}
