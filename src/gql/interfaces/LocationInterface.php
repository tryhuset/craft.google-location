<?php

namespace apt\craftgooglelocation\gql\interfaces;

use craft\gql\GqlEntityRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ConfigInterface
 */
class LocationInterface
{
    /**
     * @return string
     */
    static public function getName(): string
    {
        return 'LocationInterface';
    }

    /**
     * @return Type
     */
    static public function getType()
    {
        if ($type = GqlEntityRegistry::getEntity(self::class)) {
            return $type;
        }

        return GqlEntityRegistry::createEntity(
            self::class,
            new ObjectType(
                [
                    'name' => static::getName(),
                    'fields' => self::class . '::getFieldDefinitions',
                    'description' => 'This is the interface implemented by Craft google location.',
                ]
            )
        );
    }

    /**
     * @rejturn array
     */
    public static function getFieldDefinitions(): array
    {
        return [
            'country' => CountryInterface::getType(),
            'address' => Type::string(),
            'lat' => Type::float(),
            'lng' => Type::float(),
            'zoom' => Type::int(),
        ];
    }
}
