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
use apt\craftgooglelocation\fields\Location;

/**
 * @author    Thomas Sømoen
 * @package   CraftGoogleLocation
 * @since     1.0.0
 */
class Content extends Component
{
    // private $countries = [];
    //
    // public function __construct($config = [])
    // {
    //     parent::__construct($config);
    // }


    // Public Methods
    // =========================================================================


    /*
     * @return mixed
     */

    public function getLocationsFields()
    {
        $list = [];

        $sectionObjects = Craft::$app->sections->getAllSections();
        foreach ($sectionObjects as $section) {
            // $sections[] = [
            //     'value' => $section->handle,
            //     'label' => $section->name,
            // ];
            // $jssections[$section->handle] = [
            //     'label' => Craft::t('craft-google-location', 'Select entry type'),
            //     'types' => [],
            // ];
            // if ($section->handle === $this->section) {
            //     $types[] = [
            //         'label' => Craft::t('craft-google-location', 'Select entry type'),
            //         'value' => '',
            //     ];
            // }

            $entryTypes = $section->getEntryTypes();
            foreach ($entryTypes as $entryType) {
                // $jssections[$section->handle]['types'][$entryType->handle] = [
                //     'label' => Craft::t('craft-google-location', $entryType->name),
                //     'fields' => [[
                //         'handle' => '',
                //         'label' => Craft::t('craft-google-location', 'Select field'),
                //     ]],
                // ];
                // if ($section->handle === $this->section && $entryType->handle === $this->type) {
                //     $types[] = [
                //         'label' => Craft::t('craft-google-location', $entryType->name),
                //         'value' => $entryType->handle,
                //     ];
                //     $fields[] = [
                //         'label' => Craft::t('craft-google-location', 'Select field'),
                //         'value' => '',
                //     ];
                // }
                $fieldsObjects = $entryType->getFieldLayout()->getFields();

                foreach ($fieldsObjects as $field) {
                    if ($field instanceof Location) {
                        $list[] = [
                            'section' => $section,
                            'type' => $entryType,
                            'field' => $field,
                        ];
                    } else if($field instanceof Matrix) {
                        $blocks = array_map(function($block) {
                            $fields = array_filter($block->getFieldLayout()->getFields(), function($field) {
                                return ($field instanceof Location);
                            });
                            $fields = array_map(function($field) {
                                return [
                                    'handle' => $field->handle,
                                    'name' => $field->name,
                                ];
                            }, $fields);
                            return [
                                'handle' => $block->handle,
                                'name' => $block->name,
                                'fields' => $fields,
                            ];
                        }, $field->blockTypes);

                        d($blocks);

                        // foreach ($field->blockTypes as $block) {
                        //     $matrixFields = $block->getFieldLayout()->getFields();
                        //     foreach ($matrixFields as $matrixField) {
                        //         d($matrixField);
                        //     }
                        // }
                        $list[] = [
                            'section' => $section,
                            'type' => $entryType,
                            'field' => $field,
                        ];
                    }

                    // $jssections[$section->handle]['types'][$entryType->handle]['fields'][] = [
                    //     'handle' => $field->handle,
                    //     'label' => Craft::t('craft-google-location', $field->name),
                    // ];
                    // if ($section->handle === $this->section && $entryType->handle === $this->type) {
                    //     $fields[] = [
                    //         'label' => Craft::t('craft-google-location', $field->name),
                    //         'value' => $field->handle,
                    //     ];
                    // }
                }
            }
        }

        return $list;
    }
}
