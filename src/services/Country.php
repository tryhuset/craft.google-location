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
class Country extends Component
{
    private $countries = [];

    public function __construct($config = [])
    {
        $this->countries = [
            ['value' => 'AD', 'label' => Craft::t('craft-google-location', 'Andorra')],
            ['value' => 'AE', 'label' => Craft::t('craft-google-location', 'United Arab Emirates')],
            ['value' => 'AF', 'label' => Craft::t('craft-google-location', 'Afghanistan')],
            ['value' => 'AG', 'label' => Craft::t('craft-google-location', 'Antigua and Barbuda')],
            ['value' => 'AI', 'label' => Craft::t('craft-google-location', 'Anguilla')],
            ['value' => 'AL', 'label' => Craft::t('craft-google-location', 'Albania')],
            ['value' => 'AM', 'label' => Craft::t('craft-google-location', 'Armenia')],
            ['value' => 'AO', 'label' => Craft::t('craft-google-location', 'Angola')],
            ['value' => 'AQ', 'label' => Craft::t('craft-google-location', 'Antarctica')],
            ['value' => 'AR', 'label' => Craft::t('craft-google-location', 'Argentina')],
            ['value' => 'AS', 'label' => Craft::t('craft-google-location', 'American Samoa')],
            ['value' => 'AT', 'label' => Craft::t('craft-google-location', 'Austria')],
            ['value' => 'AU', 'label' => Craft::t('craft-google-location', 'Australia')],
            ['value' => 'AW', 'label' => Craft::t('craft-google-location', 'Aruba')],
            ['value' => 'AX', 'label' => Craft::t('craft-google-location', 'Aland Islands')],
            ['value' => 'AZ', 'label' => Craft::t('craft-google-location', 'Azerbaijan')],
            ['value' => 'BA', 'label' => Craft::t('craft-google-location', 'Bosnia and Herzegovina')],
            ['value' => 'BB', 'label' => Craft::t('craft-google-location', 'Barbados')],
            ['value' => 'BD', 'label' => Craft::t('craft-google-location', 'Bangladesh')],
            ['value' => 'BE', 'label' => Craft::t('craft-google-location', 'Belgium')],
            ['value' => 'BF', 'label' => Craft::t('craft-google-location', 'Burkina Faso')],
            ['value' => 'BG', 'label' => Craft::t('craft-google-location', 'Bulgaria')],
            ['value' => 'BH', 'label' => Craft::t('craft-google-location', 'Bahrain')],
            ['value' => 'BI', 'label' => Craft::t('craft-google-location', 'Burundi')],
            ['value' => 'BJ', 'label' => Craft::t('craft-google-location', 'Benin')],
            ['value' => 'BL', 'label' => Craft::t('craft-google-location', 'Saint Bartelemey')],
            ['value' => 'BM', 'label' => Craft::t('craft-google-location', 'Bermuda')],
            ['value' => 'BN', 'label' => Craft::t('craft-google-location', 'Brunei Darussalam')],
            ['value' => 'BO', 'label' => Craft::t('craft-google-location', 'Bolivia')],
            ['value' => 'BQ', 'label' => Craft::t('craft-google-location', 'Bonaire, Saint Eustatius and Saba')],
            ['value' => 'BR', 'label' => Craft::t('craft-google-location', 'Brazil')],
            ['value' => 'BS', 'label' => Craft::t('craft-google-location', 'Bahamas')],
            ['value' => 'BT', 'label' => Craft::t('craft-google-location', 'Bhutan')],
            ['value' => 'BV', 'label' => Craft::t('craft-google-location', 'Bouvet Island')],
            ['value' => 'BW', 'label' => Craft::t('craft-google-location', 'Botswana')],
            ['value' => 'BY', 'label' => Craft::t('craft-google-location', 'Belarus')],
            ['value' => 'BZ', 'label' => Craft::t('craft-google-location', 'Belize')],
            ['value' => 'CA', 'label' => Craft::t('craft-google-location', 'Canada')],
            ['value' => 'CC', 'label' => Craft::t('craft-google-location', 'Cocos (Keeling) Islands')],
            ['value' => 'CD', 'label' => Craft::t('craft-google-location', 'Congo, The Democratic Republic of the')],
            ['value' => 'CF', 'label' => Craft::t('craft-google-location', 'Central African Republic')],
            ['value' => 'CG', 'label' => Craft::t('craft-google-location', 'Congo')],
            ['value' => 'CH', 'label' => Craft::t('craft-google-location', 'Switzerland')],
            ['value' => 'CI', 'label' => Craft::t('craft-google-location', 'Cote d\'Ivoire')],
            ['value' => 'CK', 'label' => Craft::t('craft-google-location', 'Cook Islands')],
            ['value' => 'CL', 'label' => Craft::t('craft-google-location', 'Chile')],
            ['value' => 'CM', 'label' => Craft::t('craft-google-location', 'Cameroon')],
            ['value' => 'CN', 'label' => Craft::t('craft-google-location', 'China')],
            ['value' => 'CO', 'label' => Craft::t('craft-google-location', 'Colombia')],
            ['value' => 'CR', 'label' => Craft::t('craft-google-location', 'Costa Rica')],
            ['value' => 'CU', 'label' => Craft::t('craft-google-location', 'Cuba')],
            ['value' => 'CV', 'label' => Craft::t('craft-google-location', 'Cape Verde')],
            ['value' => 'CW', 'label' => Craft::t('craft-google-location', 'Curacao')],
            ['value' => 'CX', 'label' => Craft::t('craft-google-location', 'Christmas Island')],
            ['value' => 'CY', 'label' => Craft::t('craft-google-location', 'Cyprus')],
            ['value' => 'CZ', 'label' => Craft::t('craft-google-location', 'Czech Republic')],
            ['value' => 'DE', 'label' => Craft::t('craft-google-location', 'Germany')],
            ['value' => 'DJ', 'label' => Craft::t('craft-google-location', 'Djibouti')],
            ['value' => 'DK', 'label' => Craft::t('craft-google-location', 'Denmark')],
            ['value' => 'DM', 'label' => Craft::t('craft-google-location', 'Dominica')],
            ['value' => 'DO', 'label' => Craft::t('craft-google-location', 'Dominican Republic')],
            ['value' => 'DZ', 'label' => Craft::t('craft-google-location', 'Algeria')],
            ['value' => 'EC', 'label' => Craft::t('craft-google-location', 'Ecuador')],
            ['value' => 'EE', 'label' => Craft::t('craft-google-location', 'Estonia')],
            ['value' => 'EG', 'label' => Craft::t('craft-google-location', 'Egypt')],
            ['value' => 'EH', 'label' => Craft::t('craft-google-location', 'Western Sahara')],
            ['value' => 'ER', 'label' => Craft::t('craft-google-location', 'Eritrea')],
            ['value' => 'ES', 'label' => Craft::t('craft-google-location', 'Spain')],
            ['value' => 'ET', 'label' => Craft::t('craft-google-location', 'Ethiopia')],
            ['value' => 'FI', 'label' => Craft::t('craft-google-location', 'Finland')],
            ['value' => 'FJ', 'label' => Craft::t('craft-google-location', 'Fiji')],
            ['value' => 'FK', 'label' => Craft::t('craft-google-location', 'Falkland Islands (Malvinas)')],
            ['value' => 'FM', 'label' => Craft::t('craft-google-location', 'Micronesia, Federated States of')],
            ['value' => 'FO', 'label' => Craft::t('craft-google-location', 'Faroe Islands')],
            ['value' => 'FR', 'label' => Craft::t('craft-google-location', 'France')],
            ['value' => 'GA', 'label' => Craft::t('craft-google-location', 'Gabon')],
            ['value' => 'GB', 'label' => Craft::t('craft-google-location', 'United Kingdom')],
            ['value' => 'GD', 'label' => Craft::t('craft-google-location', 'Grenada')],
            ['value' => 'GE', 'label' => Craft::t('craft-google-location', 'Georgia')],
            ['value' => 'GF', 'label' => Craft::t('craft-google-location', 'French Guiana')],
            ['value' => 'GG', 'label' => Craft::t('craft-google-location', 'Guernsey')],
            ['value' => 'GH', 'label' => Craft::t('craft-google-location', 'Ghana')],
            ['value' => 'GI', 'label' => Craft::t('craft-google-location', 'Gibraltar')],
            ['value' => 'GL', 'label' => Craft::t('craft-google-location', 'Greenland')],
            ['value' => 'GM', 'label' => Craft::t('craft-google-location', 'Gambia')],
            ['value' => 'GN', 'label' => Craft::t('craft-google-location', 'Guinea')],
            ['value' => 'GP', 'label' => Craft::t('craft-google-location', 'Guadeloupe')],
            ['value' => 'GQ', 'label' => Craft::t('craft-google-location', 'Equatorial Guinea')],
            ['value' => 'GR', 'label' => Craft::t('craft-google-location', 'Greece')],
            ['value' => 'GS', 'label' => Craft::t('craft-google-location', 'South Georgia and the South Sandwich Islands')],
            ['value' => 'GT', 'label' => Craft::t('craft-google-location', 'Guatemala')],
            ['value' => 'GU', 'label' => Craft::t('craft-google-location', 'Guam')],
            ['value' => 'GW', 'label' => Craft::t('craft-google-location', 'Guinea-Bissau')],
            ['value' => 'GY', 'label' => Craft::t('craft-google-location', 'Guyana')],
            ['value' => 'HK', 'label' => Craft::t('craft-google-location', 'Hong Kong')],
            ['value' => 'HM', 'label' => Craft::t('craft-google-location', 'Heard Island and McDonald Islands')],
            ['value' => 'HN', 'label' => Craft::t('craft-google-location', 'Honduras')],
            ['value' => 'HR', 'label' => Craft::t('craft-google-location', 'Croatia')],
            ['value' => 'HT', 'label' => Craft::t('craft-google-location', 'Haiti')],
            ['value' => 'HU', 'label' => Craft::t('craft-google-location', 'Hungary')],
            ['value' => 'ID', 'label' => Craft::t('craft-google-location', 'Indonesia')],
            ['value' => 'IE', 'label' => Craft::t('craft-google-location', 'Ireland')],
            ['value' => 'IL', 'label' => Craft::t('craft-google-location', 'Israel')],
            ['value' => 'IM', 'label' => Craft::t('craft-google-location', 'Isle of Man')],
            ['value' => 'IN', 'label' => Craft::t('craft-google-location', 'India')],
            ['value' => 'IO', 'label' => Craft::t('craft-google-location', 'British Indian Ocean Territory')],
            ['value' => 'IQ', 'label' => Craft::t('craft-google-location', 'Iraq')],
            ['value' => 'IR', 'label' => Craft::t('craft-google-location', 'Iran, Islamic Republic of')],
            ['value' => 'IS', 'label' => Craft::t('craft-google-location', 'Iceland')],
            ['value' => 'IT', 'label' => Craft::t('craft-google-location', 'Italy')],
            ['value' => 'JE', 'label' => Craft::t('craft-google-location', 'Jersey')],
            ['value' => 'JM', 'label' => Craft::t('craft-google-location', 'Jamaica')],
            ['value' => 'JO', 'label' => Craft::t('craft-google-location', 'Jordan')],
            ['value' => 'JP', 'label' => Craft::t('craft-google-location', 'Japan')],
            ['value' => 'KE', 'label' => Craft::t('craft-google-location', 'Kenya')],
            ['value' => 'KG', 'label' => Craft::t('craft-google-location', 'Kyrgyzstan')],
            ['value' => 'KH', 'label' => Craft::t('craft-google-location', 'Cambodia')],
            ['value' => 'KI', 'label' => Craft::t('craft-google-location', 'Kiribati')],
            ['value' => 'KM', 'label' => Craft::t('craft-google-location', 'Comoros')],
            ['value' => 'KN', 'label' => Craft::t('craft-google-location', 'Saint Kitts and Nevis')],
            ['value' => 'KP', 'label' => Craft::t('craft-google-location', 'Korea, Democratic People\'s Republic of')],
            ['value' => 'KR', 'label' => Craft::t('craft-google-location', 'Korea, Republic of')],
            ['value' => 'KW', 'label' => Craft::t('craft-google-location', 'Kuwait')],
            ['value' => 'KY', 'label' => Craft::t('craft-google-location', 'Cayman Islands')],
            ['value' => 'KZ', 'label' => Craft::t('craft-google-location', 'Kazakhstan')],
            ['value' => 'LA', 'label' => Craft::t('craft-google-location', 'Lao People\'s Democratic Republic')],
            ['value' => 'LB', 'label' => Craft::t('craft-google-location', 'Lebanon')],
            ['value' => 'LC', 'label' => Craft::t('craft-google-location', 'Saint Lucia')],
            ['value' => 'LI', 'label' => Craft::t('craft-google-location', 'Liechtenstein')],
            ['value' => 'LK', 'label' => Craft::t('craft-google-location', 'Sri Lanka')],
            ['value' => 'LR', 'label' => Craft::t('craft-google-location', 'Liberia')],
            ['value' => 'LS', 'label' => Craft::t('craft-google-location', 'Lesotho')],
            ['value' => 'LT', 'label' => Craft::t('craft-google-location', 'Lithuania')],
            ['value' => 'LU', 'label' => Craft::t('craft-google-location', 'Luxembourg')],
            ['value' => 'LV', 'label' => Craft::t('craft-google-location', 'Latvia')],
            ['value' => 'LY', 'label' => Craft::t('craft-google-location', 'Libyan Arab Jamahiriya')],
            ['value' => 'MA', 'label' => Craft::t('craft-google-location', 'Morocco')],
            ['value' => 'MC', 'label' => Craft::t('craft-google-location', 'Monaco')],
            ['value' => 'MD', 'label' => Craft::t('craft-google-location', 'Moldova, Republic of')],
            ['value' => 'ME', 'label' => Craft::t('craft-google-location', 'Montenegro')],
            ['value' => 'MF', 'label' => Craft::t('craft-google-location', 'Saint Martin')],
            ['value' => 'MG', 'label' => Craft::t('craft-google-location', 'Madagascar')],
            ['value' => 'MH', 'label' => Craft::t('craft-google-location', 'Marshall Islands')],
            ['value' => 'MK', 'label' => Craft::t('craft-google-location', 'Macedonia')],
            ['value' => 'ML', 'label' => Craft::t('craft-google-location', 'Mali')],
            ['value' => 'MM', 'label' => Craft::t('craft-google-location', 'Myanmar')],
            ['value' => 'MN', 'label' => Craft::t('craft-google-location', 'Mongolia')],
            ['value' => 'MO', 'label' => Craft::t('craft-google-location', 'Macao')],
            ['value' => 'MP', 'label' => Craft::t('craft-google-location', 'Northern Mariana Islands')],
            ['value' => 'MQ', 'label' => Craft::t('craft-google-location', 'Martinique')],
            ['value' => 'MR', 'label' => Craft::t('craft-google-location', 'Mauritania')],
            ['value' => 'MS', 'label' => Craft::t('craft-google-location', 'Montserrat')],
            ['value' => 'MT', 'label' => Craft::t('craft-google-location', 'Malta')],
            ['value' => 'MU', 'label' => Craft::t('craft-google-location', 'Mauritius')],
            ['value' => 'MV', 'label' => Craft::t('craft-google-location', 'Maldives')],
            ['value' => 'MW', 'label' => Craft::t('craft-google-location', 'Malawi')],
            ['value' => 'MX', 'label' => Craft::t('craft-google-location', 'Mexico')],
            ['value' => 'MY', 'label' => Craft::t('craft-google-location', 'Malaysia')],
            ['value' => 'MZ', 'label' => Craft::t('craft-google-location', 'Mozambique')],
            ['value' => 'NA', 'label' => Craft::t('craft-google-location', 'Namibia')],
            ['value' => 'NC', 'label' => Craft::t('craft-google-location', 'New Caledonia')],
            ['value' => 'NE', 'label' => Craft::t('craft-google-location', 'Niger')],
            ['value' => 'NF', 'label' => Craft::t('craft-google-location', 'Norfolk Island')],
            ['value' => 'NG', 'label' => Craft::t('craft-google-location', 'Nigeria')],
            ['value' => 'NI', 'label' => Craft::t('craft-google-location', 'Nicaragua')],
            ['value' => 'NL', 'label' => Craft::t('craft-google-location', 'Netherlands')],
            ['value' => 'NO', 'label' => Craft::t('craft-google-location', 'Norway')],
            ['value' => 'NP', 'label' => Craft::t('craft-google-location', 'Nepal')],
            ['value' => 'NR', 'label' => Craft::t('craft-google-location', 'Nauru')],
            ['value' => 'NU', 'label' => Craft::t('craft-google-location', 'Niue')],
            ['value' => 'NZ', 'label' => Craft::t('craft-google-location', 'New Zealand')],
            ['value' => 'OM', 'label' => Craft::t('craft-google-location', 'Oman')],
            ['value' => 'PA', 'label' => Craft::t('craft-google-location', 'Panama')],
            ['value' => 'PE', 'label' => Craft::t('craft-google-location', 'Peru')],
            ['value' => 'PF', 'label' => Craft::t('craft-google-location', 'French Polynesia')],
            ['value' => 'PG', 'label' => Craft::t('craft-google-location', 'Papua New Guinea')],
            ['value' => 'PH', 'label' => Craft::t('craft-google-location', 'Philippines')],
            ['value' => 'PK', 'label' => Craft::t('craft-google-location', 'Pakistan')],
            ['value' => 'PL', 'label' => Craft::t('craft-google-location', 'Poland')],
            ['value' => 'PM', 'label' => Craft::t('craft-google-location', 'Saint Pierre and Miquelon')],
            ['value' => 'PN', 'label' => Craft::t('craft-google-location', 'Pitcairn')],
            ['value' => 'PR', 'label' => Craft::t('craft-google-location', 'Puerto Rico')],
            ['value' => 'PS', 'label' => Craft::t('craft-google-location', 'Palestinian Territory')],
            ['value' => 'PT', 'label' => Craft::t('craft-google-location', 'Portugal')],
            ['value' => 'PW', 'label' => Craft::t('craft-google-location', 'Palau')],
            ['value' => 'PY', 'label' => Craft::t('craft-google-location', 'Paraguay')],
            ['value' => 'QA', 'label' => Craft::t('craft-google-location', 'Qatar')],
            ['value' => 'RE', 'label' => Craft::t('craft-google-location', 'Reunion')],
            ['value' => 'RO', 'label' => Craft::t('craft-google-location', 'Romania')],
            ['value' => 'RS', 'label' => Craft::t('craft-google-location', 'Serbia')],
            ['value' => 'RU', 'label' => Craft::t('craft-google-location', 'Russian Federation')],
            ['value' => 'RW', 'label' => Craft::t('craft-google-location', 'Rwanda')],
            ['value' => 'SA', 'label' => Craft::t('craft-google-location', 'Saudi Arabia')],
            ['value' => 'SB', 'label' => Craft::t('craft-google-location', 'Solomon Islands')],
            ['value' => 'SC', 'label' => Craft::t('craft-google-location', 'Seychelles')],
            ['value' => 'SD', 'label' => Craft::t('craft-google-location', 'Sudan')],
            ['value' => 'SE', 'label' => Craft::t('craft-google-location', 'Sweden')],
            ['value' => 'SG', 'label' => Craft::t('craft-google-location', 'Singapore')],
            ['value' => 'SH', 'label' => Craft::t('craft-google-location', 'Saint Helena')],
            ['value' => 'SI', 'label' => Craft::t('craft-google-location', 'Slovenia')],
            ['value' => 'SJ', 'label' => Craft::t('craft-google-location', 'Svalbard and Jan Mayen')],
            ['value' => 'SK', 'label' => Craft::t('craft-google-location', 'Slovakia')],
            ['value' => 'SL', 'label' => Craft::t('craft-google-location', 'Sierra Leone')],
            ['value' => 'SM', 'label' => Craft::t('craft-google-location', 'San Marino')],
            ['value' => 'SN', 'label' => Craft::t('craft-google-location', 'Senegal')],
            ['value' => 'SO', 'label' => Craft::t('craft-google-location', 'Somalia')],
            ['value' => 'SR', 'label' => Craft::t('craft-google-location', 'Suriname')],
            ['value' => 'SS', 'label' => Craft::t('craft-google-location', 'South Sudan')],
            ['value' => 'ST', 'label' => Craft::t('craft-google-location', 'Sao Tome and Principe')],
            ['value' => 'SV', 'label' => Craft::t('craft-google-location', 'El Salvador')],
            ['value' => 'SX', 'label' => Craft::t('craft-google-location', 'Sint Maarten')],
            ['value' => 'SY', 'label' => Craft::t('craft-google-location', 'Syrian Arab Republic')],
            ['value' => 'SZ', 'label' => Craft::t('craft-google-location', 'Swaziland')],
            ['value' => 'TC', 'label' => Craft::t('craft-google-location', 'Turks and Caicos Islands')],
            ['value' => 'TD', 'label' => Craft::t('craft-google-location', 'Chad')],
            ['value' => 'TF', 'label' => Craft::t('craft-google-location', 'French Southern Territories')],
            ['value' => 'TG', 'label' => Craft::t('craft-google-location', 'Togo')],
            ['value' => 'TH', 'label' => Craft::t('craft-google-location', 'Thailand')],
            ['value' => 'TJ', 'label' => Craft::t('craft-google-location', 'Tajikistan')],
            ['value' => 'TK', 'label' => Craft::t('craft-google-location', 'Tokelau')],
            ['value' => 'TL', 'label' => Craft::t('craft-google-location', 'Timor-Leste')],
            ['value' => 'TM', 'label' => Craft::t('craft-google-location', 'Turkmenistan')],
            ['value' => 'TN', 'label' => Craft::t('craft-google-location', 'Tunisia')],
            ['value' => 'TO', 'label' => Craft::t('craft-google-location', 'Tonga')],
            ['value' => 'TR', 'label' => Craft::t('craft-google-location', 'Turkey')],
            ['value' => 'TT', 'label' => Craft::t('craft-google-location', 'Trinidad and Tobago')],
            ['value' => 'TV', 'label' => Craft::t('craft-google-location', 'Tuvalu')],
            ['value' => 'TW', 'label' => Craft::t('craft-google-location', 'Taiwan')],
            ['value' => 'TZ', 'label' => Craft::t('craft-google-location', 'Tanzania, United Republic of')],
            ['value' => 'UA', 'label' => Craft::t('craft-google-location', 'Ukraine')],
            ['value' => 'UG', 'label' => Craft::t('craft-google-location', 'Uganda')],
            ['value' => 'UM', 'label' => Craft::t('craft-google-location', 'United States Minor Outlying Islands')],
            ['value' => 'US', 'label' => Craft::t('craft-google-location', 'United States')],
            ['value' => 'UY', 'label' => Craft::t('craft-google-location', 'Uruguay')],
            ['value' => 'UZ', 'label' => Craft::t('craft-google-location', 'Uzbekistan')],
            ['value' => 'VA', 'label' => Craft::t('craft-google-location', 'Holy See (Vatican City State)')],
            ['value' => 'VC', 'label' => Craft::t('craft-google-location', 'Saint Vincent and the Grenadines')],
            ['value' => 'VE', 'label' => Craft::t('craft-google-location', 'Venezuela')],
            ['value' => 'VG', 'label' => Craft::t('craft-google-location', 'Virgin Islands, British')],
            ['value' => 'VI', 'label' => Craft::t('craft-google-location', 'Virgin Islands, U.S.')],
            ['value' => 'VN', 'label' => Craft::t('craft-google-location', 'Vietnam')],
            ['value' => 'VU', 'label' => Craft::t('craft-google-location', 'Vanuatu')],
            ['value' => 'WF', 'label' => Craft::t('craft-google-location', 'Wallis and Futuna')],
            ['value' => 'WS', 'label' => Craft::t('craft-google-location', 'Samoa')],
            ['value' => 'YE', 'label' => Craft::t('craft-google-location', 'Yemen')],
            ['value' => 'YT', 'label' => Craft::t('craft-google-location', 'Mayotte')],
            ['value' => 'ZA', 'label' => Craft::t('craft-google-location', 'South Africa')],
            ['value' => 'ZM', 'label' => Craft::t('craft-google-location', 'Zambia')],
            ['value' => 'ZW', 'label' => Craft::t('craft-google-location', 'Zimbabwe')],
        ];
        parent::__construct($config);
    }


    // Public Methods
    // =========================================================================

    public function getCountryName($code)
    {
        $name = null;
        foreach ($this->countries as $country) {
            if ($country['value'] === $code) {
                $name = $country['label'];
                break;
            }
        }

        return $name;
    }

    /*
     * @return mixed
     */
    public function getCountries()
    {
        $countries = $this->countries;

        usort($countries, function($a, $b) {
            return strcasecmp($a['label'], $b['label']);
        });

        return $countries;
    }
}
