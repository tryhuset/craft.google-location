<?php
namespace apt\craftgooglelocation\integrations\feedme;

use Cake\Utility\Hash;
use craft\feedme\base\Field;
use craft\feedme\base\FieldInterface;
use craft\feedme\helpers\DataHelper;
use apt\craftgooglelocation\fields\Location as LocationField;

class Location extends Field implements FieldInterface
{
    public static $name = 'Location';
	public static $class = LocationField::class;

    public function getMappingTemplate ()
	{
		return 'google-location/_components/fields/Location_feedme';
	}

    public function parseField ()
	{
		$preppedData = [];
		$fields = Hash::get($this->fieldInfo, 'fields');

		if (!$fields)
			return null;

		foreach ($fields as $subFieldHandle => $subFieldInfo)
		{
			$preppedData[$subFieldHandle] = DataHelper::fetchValue(
				$this->feedData,
				$subFieldInfo
			);

            if (empty($preppedData[$subFieldHandle])) {
                $preppedData[$subFieldHandle] = $subFieldInfo['default'];
            }
		}

		if (!$preppedData)
			return null;
		return $preppedData;
	}
}
