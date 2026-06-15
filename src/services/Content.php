<?php
/**
 * Craft Google Location plugin for Craft CMS 3.x
 *
 * asdfasdf
 *
 * @link      https://apt.no/
 * @copyright Copyright (c) 2018 Thomas Sømoen
 */

namespace apt\craftgooglelocation\services;

use apt\craftgooglelocation\CraftGoogleLocation;

use Craft;
use craft\base\Component;
use craft\fields\Matrix;
use craft\elements\Entry;
use craft\elements\db\EntryQuery;
use apt\craftgooglelocation\fields\Location;
use apt\craftgooglelocation\models\Location as LocationModel;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class Content extends Component
{
    private $sections = [];

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->sections = $this->getSections();
    }

    private function getAssociativeArray($items) : array
    {
        $assoc = [];
        foreach ($items as $item) {
            $assoc[$item['handle']] = $item;
        }

        return $assoc;
    }

    /*
     * @return array
     */
    private function getMatrixBlocks($blocks) : array
    {
        $blocks = array_map(function($block) {
            $fields = array_filter($block->getFieldLayout()->getCustomFields(), function($field) {
                return ($field instanceof Location);
            });
            $assoc = [];
            foreach ($fields as $field) {
                $assoc[$field->handle] = [
                    'type' => 'location',
                    'handle' => $field->handle,
                    'name' => Craft::t('site', $field->name),
                ];
            }

            return [
                'handle' => $block->handle,
                'name' => Craft::t('site', $block->name),
                'fields' => $assoc,
            ];
        }, $blocks);

        $blocks = array_filter($blocks, function($block) {
            return count($block['fields']) > 0;
        });

        return $this->getAssociativeArray($blocks);
    }

    /*
     * @return array
     */
    private function getFields($fields) : array
    {
        $fields = array_filter($fields, function($field) {
            if ($field instanceof Location) {
                return true;
            }
            if ($field instanceof Matrix) {
                return true;
            }
            return false;
        });

        $fields = array_map(function($field) {
            if($field instanceof Matrix) {
                $blocks = $this->getMatrixBlocks($field->getEntryTypes());
                return [
                    'type' => 'matrix',
                    'handle' => $field->handle,
                    'name' => Craft::t('site', $field->name),
                    'blocks' => $blocks,
                ];
            }

            return [
                'type' => 'location',
                'handle' => $field->handle,
                'name' => Craft::t('site', $field->name),
            ];
        }, $fields);

        $fields = array_filter($fields, function($field) {
            if ($field['type'] === 'location') {
                return true;
            }
            return count($field['blocks']) > 0;
        });

        return $this->getAssociativeArray($fields);
    }

    /*
     * @return array
     */
    private function getEntryTypes($types) : array
    {
        $types = array_map(function($type) {
            $fields = $this->getFields($type->getFieldLayout()->getCustomFields());

            return [
                'handle' => $type->handle,
                'name' => Craft::t('site', $type->name),
                'fields' => $fields,
            ];
        }, $types);

        $types = array_filter($types, function($type) {
            return count($type['fields']) > 0;
        });

        return $this->getAssociativeArray($types);
    }

    /*
     * @return array
     */
    private function getSections() : array
    {
        $sections = array_map(function($section) {
            return [
                'handle' => $section->handle,
                'name' => Craft::t('site', $section->name),
                'types' => $this->getEntryTypes($section->getEntryTypes()),
            ];
        }, Craft::$app->entries->getAllSections());

        $sections = array_filter($sections, function($section) {
            return count($section['types']) > 0;
        });

        return $this->getAssociativeArray($sections);
    }

    public function getOptions() : array
    {
        return $this->sections;
    }

    /*
     * @return array
     */
    public function getSectionOptions() : array
    {
        return array_map(function($section){
            return [
                'value' => $section['handle'],
                'label' => $section['name'],
            ];
        }, $this->sections);
    }

    /*
     * @return array
     */
    public function getTypeOptions($key) : array
    {
        if (empty($key)) {
            return [];
        }

        if (!array_key_exists($key, $this->sections)) {
            return [];
        }

        return array_map(function($type) {
            return [
                'value' => $type['handle'],
                'label' => $type['name'],
            ];
        }, $this->sections[$key]['types']);
    }

    /*
     * @return array
     */
    public function getFieldOptions($sectionKey, $typeKey) : array
    {
        if (empty($sectionKey) || empty($typeKey)) {
            return [];
        }

        if (!array_key_exists($sectionKey, $this->sections)) {
            return [];
        }

        $section = $this->sections[$sectionKey];

        if (!array_key_exists($typeKey, $section['types'])) {
            return [];
        }

        $type =  $section['types'][$typeKey];

        return array_map(function($field) {
            return [
                'value' => $field['handle'],
                'label' => $field['name'],
            ];
        }, $type['fields']);
    }

    public function getMatrixBlocksOptions($sectionKey, $typeKey, $fieldKey) : array
    {
        if (empty($sectionKey) || empty($typeKey)) {
            return [];
        }

        if (!array_key_exists($sectionKey, $this->sections)) {
            return [];
        }

        $section = $this->sections[$sectionKey];

        if (!array_key_exists($typeKey, $section['types'])) {
            return [];
        }

        $type =  $section['types'][$typeKey];

        if (!array_key_exists($fieldKey, $type['fields'])) {
            return [];
        }

        $field = $type['fields'][$fieldKey];

        if ($field['type'] === 'location') {
            return [];
        }

        return array_map(function($block) {
            return [
                'value' => $block['handle'],
                'label' => $block['name'],
            ];
        }, $field['blocks']);
    }

    public function getMatrixBlockFieldOptions($sectionKey, $typeKey, $fieldKey, $matrixBlockKey) : array
    {
        if (empty($sectionKey) || empty($typeKey)) {
            return [];
        }

        if (!array_key_exists($sectionKey, $this->sections)) {
            return [];
        }

        $section = $this->sections[$sectionKey];

        if (!array_key_exists($typeKey, $section['types'])) {
            return [];
        }

        $type =  $section['types'][$typeKey];

        if (!array_key_exists($fieldKey, $type['fields'])) {
            return [];
        }

        $field = $type['fields'][$fieldKey];

        if ($field['type'] === 'location') {
            return [];
        }

        if (!array_key_exists($matrixBlockKey, $field['blocks'])) {
            return [];
        }

        $block = $field['blocks'][$matrixBlockKey];

        return array_map(function($field) {
            return [
                'value' => $field['handle'],
                'label' => $field['name'],
            ];
        }, $block['fields']);
    }

    public function getLocations($settings) : array
    {
        $entries = Entry::find()->section($settings['section'])->type($settings['type'])->all();
        $locations = [];

        foreach ($entries as $entry) {
            $location = $entry[$settings['field']];
            if ($location instanceof LocationModel) {
                $locations[] = [
                    'title' => $entry->title,
                    'position' => [
                        'lat' => floatval($location->lat),
                        'lng' => floatval($location->lng),
                    ],
                ];
            } else if ($location instanceof EntryQuery) {
                foreach ($location->type($settings['matrixBlock'])->all() as $block) {
                    $location = $block[$settings['matrixBlockField']];
                    $locations[] = [
                        'title' => $entry->title,
                        'position' => [
                            'lat' => floatval($location->lat),
                            'lng' => floatval($location->lng),
                        ],
                    ];
                }
            }
        }
        return $locations;
    }
}
