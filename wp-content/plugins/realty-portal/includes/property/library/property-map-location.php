<?php
if ( ! function_exists( 'rp_list_country' ) ) :

	/**
	 * Text list of all countries in world
	 *
	 * @return mixed|void
	 */
	function rp_list_country() {

		$list_country = array(

			array(
				'label' => 'Afghanistan',
				'value' => 'AF',
			),
			array(
				'label' => 'Åland Islands',
				'value' => 'AX',
			),
			array(
				'label' => 'Albania',
				'value' => 'AL',
			),
			array(
				'label' => 'Algeria',
				'value' => 'DZ',
			),
			array(
				'label' => 'American Samoa',
				'value' => 'AS',
			),
			array(
				'label' => 'Andorra',
				'value' => 'AD',
			),
			array(
				'label' => 'Angola',
				'value' => 'AO',
			),
			array(
				'label' => 'Anguilla',
				'value' => 'AI',
			),
			array(
				'label' => 'Antarctica',
				'value' => 'AQ',
			),
			array(
				'label' => 'Antigua and Barbuda',
				'value' => 'AG',
			),
			array(
				'label' => 'Argentina',
				'value' => 'AR',
			),
			array(
				'label' => 'Armenia',
				'value' => 'AM',
			),
			array(
				'label' => 'Aruba',
				'value' => 'AW',
			),
			array(
				'label' => 'Australia',
				'value' => 'AU',
			),
			array(
				'label' => 'Austria',
				'value' => 'AT',
			),
			array(
				'label' => 'Azerbaijan',
				'value' => 'AZ',
			),
			array(
				'label' => 'Bahamas',
				'value' => 'BS',
			),
			array(
				'label' => 'Bahrain',
				'value' => 'BH',
			),
			array(
				'label' => 'Bangladesh',
				'value' => 'BD',
			),
			array(
				'label' => 'Barbados',
				'value' => 'BB',
			),
			array(
				'label' => 'Belarus',
				'value' => 'BY',
			),
			array(
				'label' => 'Belgium',
				'value' => 'BE',
			),
			array(
				'label' => 'Belize',
				'value' => 'BZ',
			),
			array(
				'label' => 'Benin',
				'value' => 'BJ',
			),
			array(
				'label' => 'Bermuda',
				'value' => 'BM',
			),
			array(
				'label' => 'Bhutan',
				'value' => 'BT',
			),
			array(
				'label' => 'Bolivia, Plurinational State of',
				'value' => 'BO',
			),
			array(
				'label' => 'Bonaire, Sint Eustatius and Saba',
				'value' => 'BQ',
			),
			array(
				'label' => 'Bosnia and Herzegovina',
				'value' => 'BA',
			),
			array(
				'label' => 'Botswana',
				'value' => 'BW',
			),
			array(
				'label' => 'Bouvet Island',
				'value' => 'BV',
			),
			array(
				'label' => 'Brazil',
				'value' => 'BR',
			),
			array(
				'label' => 'British Indian Ocean Territory',
				'value' => 'IO',
			),
			array(
				'label' => 'Brunei Darussalam',
				'value' => 'BN',
			),
			array(
				'label' => 'Bulgaria',
				'value' => 'BG',
			),
			array(
				'label' => 'Burkina Faso',
				'value' => 'BF',
			),
			array(
				'label' => 'Burundi',
				'value' => 'BI',
			),
			array(
				'label' => 'Cambodia',
				'value' => 'KH',
			),
			array(
				'label' => 'Cameroon',
				'value' => 'CM',
			),
			array(
				'label' => 'Canada',
				'value' => 'CA',
			),
			array(
				'label' => 'Cape Verde',
				'value' => 'CV',
			),
			array(
				'label' => 'Cayman Islands',
				'value' => 'KY',
			),
			array(
				'label' => 'Central African Republic',
				'value' => 'CF',
			),
			array(
				'label' => 'Chad',
				'value' => 'TD',
			),
			array(
				'label' => 'Chile',
				'value' => 'CL',
			),
			array(
				'label' => 'China',
				'value' => 'CN',
			),
			array(
				'label' => 'Christmas Island',
				'value' => 'CX',
			),
			array(
				'label' => 'Cocos (Keeling) Islands',
				'value' => 'CC',
			),
			array(
				'label' => 'Colombia',
				'value' => 'CO',
			),
			array(
				'label' => 'Comoros',
				'value' => 'KM',
			),
			array(
				'label' => 'Congo',
				'value' => 'CG',
			),
			array(
				'label' => 'Congo, the Democratic Republic of the',
				'value' => 'CD',
			),
			array(
				'label' => 'Cook Islands',
				'value' => 'CK',
			),
			array(
				'label' => 'Costa Rica',
				'value' => 'CR',
			),
			array(
				'label' => 'Côte d\'Ivoire',
				'value' => 'CI',
			),
			array(
				'label' => 'Croatia',
				'value' => 'HR',
			),
			array(
				'label' => 'Cuba',
				'value' => 'CU',
			),
			array(
				'label' => 'Curaçao',
				'value' => 'CW',
			),
			array(
				'label' => 'Cyprus',
				'value' => 'CY',
			),
			array(
				'label' => 'Czech Republic',
				'value' => 'CZ',
			),
			array(
				'label' => 'Denmark',
				'value' => 'DK',
			),
			array(
				'label' => 'Djibouti',
				'value' => 'DJ',
			),
			array(
				'label' => 'Dominica',
				'value' => 'DM',
			),
			array(
				'label' => 'Dominican Republic',
				'value' => 'DO',
			),
			array(
				'label' => 'Ecuador',
				'value' => 'EC',
			),
			array(
				'label' => 'Egypt',
				'value' => 'EG',
			),
			array(
				'label' => 'El Salvador',
				'value' => 'SV',
			),
			array(
				'label' => 'Equatorial Guinea',
				'value' => 'GQ',
			),
			array(
				'label' => 'Eritrea',
				'value' => 'ER',
			),
			array(
				'label' => 'Estonia',
				'value' => 'EE',
			),
			array(
				'label' => 'Ethiopia',
				'value' => 'ET',
			),
			array(
				'label' => 'Falkland Islands (Malvinas)',
				'value' => 'FK',
			),
			array(
				'label' => 'Faroe Islands',
				'value' => 'FO',
			),
			array(
				'label' => 'Fiji',
				'value' => 'FJ',
			),
			array(
				'label' => 'Finland',
				'value' => 'FI',
			),
			array(
				'label' => 'France',
				'value' => 'FR',
			),
			array(
				'label' => 'French Guiana',
				'value' => 'GF',
			),
			array(
				'label' => 'French Polynesia',
				'value' => 'PF',
			),
			array(
				'label' => 'French Southern Territories',
				'value' => 'TF',
			),
			array(
				'label' => 'Gabon',
				'value' => 'GA',
			),
			array(
				'label' => 'Gambia',
				'value' => 'GM',
			),
			array(
				'label' => 'Georgia',
				'value' => 'GE',
			),
			array(
				'label' => 'Germany',
				'value' => 'DE',
			),
			array(
				'label' => 'Ghana',
				'value' => 'GH',
			),
			array(
				'label' => 'Gibraltar',
				'value' => 'GI',
			),
			array(
				'label' => 'Greece',
				'value' => 'GR',
			),
			array(
				'label' => 'Greenland',
				'value' => 'GL',
			),
			array(
				'label' => 'Grenada',
				'value' => 'GD',
			),
			array(
				'label' => 'Guadeloupe',
				'value' => 'GP',
			),
			array(
				'label' => 'Guam',
				'value' => 'GU',
			),
			array(
				'label' => 'Guatemala',
				'value' => 'GT',
			),
			array(
				'label' => 'Guernsey',
				'value' => 'GG',
			),
			array(
				'label' => 'Guinea',
				'value' => 'GN',
			),
			array(
				'label' => 'Guinea-Bissau',
				'value' => 'GW',
			),
			array(
				'label' => 'Guyana',
				'value' => 'GY',
			),
			array(
				'label' => 'Haiti',
				'value' => 'HT',
			),
			array(
				'label' => 'Heard Island and McDonald Islands',
				'value' => 'HM',
			),
			array(
				'label' => 'Holy See (Vatican City State)',
				'value' => 'VA',
			),
			array(
				'label' => 'Honduras',
				'value' => 'HN',
			),
			array(
				'label' => 'Hong Kong',
				'value' => 'HK',
			),
			array(
				'label' => 'Hungary',
				'value' => 'HU',
			),
			array(
				'label' => 'Iceland',
				'value' => 'IS',
			),
			array(
				'label' => 'India',
				'value' => 'IN',
			),
			array(
				'label' => 'Indonesia',
				'value' => 'ID',
			),
			array(
				'label' => 'Iran, Islamic Republic of',
				'value' => 'IR',
			),
			array(
				'label' => 'Iraq',
				'value' => 'IQ',
			),
			array(
				'label' => 'Ireland',
				'value' => 'IE',
			),
			array(
				'label' => 'Isle of Man',
				'value' => 'IM',
			),
			array(
				'label' => 'Israel',
				'value' => 'IL',
			),
			array(
				'label' => 'Italy',
				'value' => 'IT',
			),
			array(
				'label' => 'Jamaica',
				'value' => 'JM',
			),
			array(
				'label' => 'Japan',
				'value' => 'JP',
			),
			array(
				'label' => 'Jersey',
				'value' => 'JE',
			),
			array(
				'label' => 'Jordan',
				'value' => 'JO',
			),
			array(
				'label' => 'Kazakhstan',
				'value' => 'KZ',
			),
			array(
				'label' => 'Kenya',
				'value' => 'KE',
			),
			array(
				'label' => 'Kiribati',
				'value' => 'KI',
			),
			array(
				'label' => 'Korea, Democratic People\'s Republic of',
				'value' => 'KP',
			),
			array(
				'label' => 'Korea, Republic of',
				'value' => 'KR',
			),
			array(
				'label' => 'Kuwait',
				'value' => 'KW',
			),
			array(
				'label' => 'Kyrgyzstan',
				'value' => 'KG',
			),
			array(
				'label' => 'Lao People\'s Democratic Republic',
				'value' => 'LA',
			),
			array(
				'label' => 'Latvia',
				'value' => 'LV',
			),
			array(
				'label' => 'Lebanon',
				'value' => 'LB',
			),
			array(
				'label' => 'Lesotho',
				'value' => 'LS',
			),
			array(
				'label' => 'Liberia',
				'value' => 'LR',
			),
			array(
				'label' => 'Libya',
				'value' => 'LY',
			),
			array(
				'label' => 'Liechtenstein',
				'value' => 'LI',
			),
			array(
				'label' => 'Lithuania',
				'value' => 'LT',
			),
			array(
				'label' => 'Luxembourg',
				'value' => 'LU',
			),
			array(
				'label' => 'Macao',
				'value' => 'MO',
			),
			array(
				'label' => 'Macedonia, the Former Yugoslav Republic of',
				'value' => 'MK',
			),
			array(
				'label' => 'Madagascar',
				'value' => 'MG',
			),
			array(
				'label' => 'Malawi',
				'value' => 'MW',
			),
			array(
				'label' => 'Malaysia',
				'value' => 'MY',
			),
			array(
				'label' => 'Maldives',
				'value' => 'MV',
			),
			array(
				'label' => 'Mali',
				'value' => 'ML',
			),
			array(
				'label' => 'Malta',
				'value' => 'MT',
			),
			array(
				'label' => 'Marshall Islands',
				'value' => 'MH',
			),
			array(
				'label' => 'Martinique',
				'value' => 'MQ',
			),
			array(
				'label' => 'Mauritania',
				'value' => 'MR',
			),
			array(
				'label' => 'Mauritius',
				'value' => 'MU',
			),
			array(
				'label' => 'Mayotte',
				'value' => 'YT',
			),
			array(
				'label' => 'Mexico',
				'value' => 'MX',
			),
			array(
				'label' => 'Micronesia, Federated States of',
				'value' => 'FM',
			),
			array(
				'label' => 'Moldova, Republic of',
				'value' => 'MD',
			),
			array(
				'label' => 'Monaco',
				'value' => 'MC',
			),
			array(
				'label' => 'Mongolia',
				'value' => 'MN',
			),
			array(
				'label' => 'Montenegro',
				'value' => 'ME',
			),
			array(
				'label' => 'Montserrat',
				'value' => 'MS',
			),
			array(
				'label' => 'Morocco',
				'value' => 'MA',
			),
			array(
				'label' => 'Mozambique',
				'value' => 'MZ',
			),
			array(
				'label' => 'Myanmar',
				'value' => 'MM',
			),
			array(
				'label' => 'Namibia',
				'value' => 'NA',
			),
			array(
				'label' => 'Nauru',
				'value' => 'NR',
			),
			array(
				'label' => 'Nepal',
				'value' => 'NP',
			),
			array(
				'label' => 'Netherlands',
				'value' => 'NL',
			),
			array(
				'label' => 'New Caledonia',
				'value' => 'NC',
			),
			array(
				'label' => 'New Zealand',
				'value' => 'NZ',
			),
			array(
				'label' => 'Nicaragua',
				'value' => 'NI',
			),
			array(
				'label' => 'Niger',
				'value' => 'NE',
			),
			array(
				'label' => 'Nigeria',
				'value' => 'NG',
			),
			array(
				'label' => 'Niue',
				'value' => 'NU',
			),
			array(
				'label' => 'Norfolk Island',
				'value' => 'NF',
			),
			array(
				'label' => 'Northern Mariana Islands',
				'value' => 'MP',
			),
			array(
				'label' => 'Norway',
				'value' => 'NO',
			),
			array(
				'label' => 'Oman',
				'value' => 'OM',
			),
			array(
				'label' => 'Pakistan',
				'value' => 'PK',
			),
			array(
				'label' => 'Palau',
				'value' => 'PW',
			),
			array(
				'label' => 'Palestine, State of',
				'value' => 'PS',
			),
			array(
				'label' => 'Panama',
				'value' => 'PA',
			),
			array(
				'label' => 'Papua New Guinea',
				'value' => 'PG',
			),
			array(
				'label' => 'Paraguay',
				'value' => 'PY',
			),
			array(
				'label' => 'Peru',
				'value' => 'PE',
			),
			array(
				'label' => 'Philippines',
				'value' => 'PH',
			),
			array(
				'label' => 'Pitcairn',
				'value' => 'PN',
			),
			array(
				'label' => 'Poland',
				'value' => 'PL',
			),
			array(
				'label' => 'Portugal',
				'value' => 'PT',
			),
			array(
				'label' => 'Puerto Rico',
				'value' => 'PR',
			),
			array(
				'label' => 'Qatar',
				'value' => 'QA',
			),
			array(
				'label' => 'Réunion',
				'value' => 'RE',
			),
			array(
				'label' => 'Romania',
				'value' => 'RO',
			),
			array(
				'label' => 'Russian Federation',
				'value' => 'RU',
			),
			array(
				'label' => 'Rwanda',
				'value' => 'RW',
			),
			array(
				'label' => 'Saint Barthélemy',
				'value' => 'BL',
			),
			array(
				'label' => 'Saint Helena, Ascension and Tristan da Cunha',
				'value' => 'SH',
			),
			array(
				'label' => 'Saint Kitts and Nevis',
				'value' => 'KN',
			),
			array(
				'label' => 'Saint Lucia',
				'value' => 'LC',
			),
			array(
				'label' => 'Saint Martin (French part)',
				'value' => 'MF',
			),
			array(
				'label' => 'Saint Pierre and Miquelon',
				'value' => 'PM',
			),
			array(
				'label' => 'Saint Vincent and the Grenadines',
				'value' => 'VC',
			),
			array(
				'label' => 'Samoa',
				'value' => 'WS',
			),
			array(
				'label' => 'San Marino',
				'value' => 'SM',
			),
			array(
				'label' => 'Sao Tome and Principe',
				'value' => 'ST',
			),
			array(
				'label' => 'Saudi Arabia',
				'value' => 'SA',
			),
			array(
				'label' => 'Senegal',
				'value' => 'SN',
			),
			array(
				'label' => 'Serbia',
				'value' => 'RS',
			),
			array(
				'label' => 'Seychelles',
				'value' => 'SC',
			),
			array(
				'label' => 'Sierra Leone',
				'value' => 'SL',
			),
			array(
				'label' => 'Singapore',
				'value' => 'SG',
			),
			array(
				'label' => 'Sint Maarten (Dutch part)',
				'value' => 'SX',
			),
			array(
				'label' => 'Slovakia',
				'value' => 'SK',
			),
			array(
				'label' => 'Slovenia',
				'value' => 'SI',
			),
			array(
				'label' => 'Solomon Islands',
				'value' => 'SB',
			),
			array(
				'label' => 'Somalia',
				'value' => 'SO',
			),
			array(
				'label' => 'South Africa',
				'value' => 'ZA',
			),
			array(
				'label' => 'South Georgia and the South Sandwich Islands',
				'value' => 'GS',
			),
			array(
				'label' => 'South Sudan',
				'value' => 'SS',
			),
			array(
				'label' => 'Spain',
				'value' => 'ES',
			),
			array(
				'label' => 'Sri Lanka',
				'value' => 'LK',
			),
			array(
				'label' => 'Sudan',
				'value' => 'SD',
			),
			array(
				'label' => 'Suriname',
				'value' => 'SR',
			),
			array(
				'label' => 'Svalbard and Jan Mayen',
				'value' => 'SJ',
			),
			array(
				'label' => 'Swaziland',
				'value' => 'SZ',
			),
			array(
				'label' => 'Sweden',
				'value' => 'SE',
			),
			array(
				'label' => 'Switzerland',
				'value' => 'CH',
			),
			array(
				'label' => 'Syrian Arab Republic',
				'value' => 'SY',
			),
			array(
				'label' => 'Taiwan, Province of China',
				'value' => 'TW',
			),
			array(
				'label' => 'Tajikistan',
				'value' => 'TJ',
			),
			array(
				'label' => 'Tanzania, United Republic of',
				'value' => 'TZ',
			),
			array(
				'label' => 'Thailand',
				'value' => 'TH',
			),
			array(
				'label' => 'Timor-Leste',
				'value' => 'TL',
			),
			array(
				'label' => 'Togo',
				'value' => 'TG',
			),
			array(
				'label' => 'Tokelau',
				'value' => 'TK',
			),
			array(
				'label' => 'Tonga',
				'value' => 'TO',
			),
			array(
				'label' => 'Trinidad and Tobago',
				'value' => 'TT',
			),
			array(
				'label' => 'Tunisia',
				'value' => 'TN',
			),
			array(
				'label' => 'Turkey',
				'value' => 'TR',
			),
			array(
				'label' => 'Turkmenistan',
				'value' => 'TM',
			),
			array(
				'label' => 'Turks and Caicos Islands',
				'value' => 'TC',
			),
			array(
				'label' => 'Tuvalu',
				'value' => 'TV',
			),
			array(
				'label' => 'Uganda',
				'value' => 'UG',
			),
			array(
				'label' => 'Ukraine',
				'value' => 'UA',
			),
			array(
				'label' => 'United Arab Emirates',
				'value' => 'AE',
			),
			array(
				'label' => 'United Kingdom',
				'value' => 'GB',
			),
			array(
				'label' => 'United States',
				'value' => 'US',
			),
			array(
				'label' => 'United States Minor Outlying Islands',
				'value' => 'UM',
			),
			array(
				'label' => 'Uruguay',
				'value' => 'UY',
			),
			array(
				'label' => 'Uzbekistan',
				'value' => 'UZ',
			),
			array(
				'label' => 'Vanuatu',
				'value' => 'VU',
			),
			array(
				'label' => 'Venezuela, Bolivarian Republic of',
				'value' => 'VE',
			),
			array(
				'label' => 'Việt Nam',
				'value' => 'VN',
			),
			array(
				'label' => 'Virgin Islands, British',
				'value' => 'VG',
			),
			array(
				'label' => 'Virgin Islands, U.S.',
				'value' => 'VI',
			),
			array(
				'label' => 'Wallis and Futuna',
				'value' => 'WF',
			),
			array(
				'label' => 'Western Sahara',
				'value' => 'EH',
			),
			array(
				'label' => 'Yemen',
				'value' => 'YE',
			),
			array(
				'label' => 'Zambia',
				'value' => 'ZM',
			),
			array(
				'label' => 'Zimbabwe',
				'value' => 'Z',
			),

		);

		return apply_filters( 'rp_list_country', $list_country );
	}

endif;

if ( ! function_exists( 'rp_map_location_type' ) ) :

	/**
	 * Get list location type
	 *
	 * @return mixed|void
	 */
	function rp_map_location_type() {

		$list_map_location = array(
			'(regions)'     => esc_html__( 'Administrative Regions', 'realty-portal' ),
			'(cities)'      => esc_html__( 'Cities', 'realty-portal' ),
			'establishment' => esc_html__( 'Establishment ( Business location )', 'realty-portal' ),
			'geocode'       => esc_html__( 'Full address', 'realty-portal' ),
		);

		return apply_filters( 'rp_map_location_type', $list_map_location );
	}

endif;

if ( ! function_exists( 'rp_get_country_ISO_code' ) ) :

	/**
	 * Get country ISO code

	 * @return mixed|void
	 */
	function rp_get_country_ISO_code() {

		$country_ISO_code = array(
			'all' => 'All',
			'AF'  => 'Afghanistan',
			'AX'  => 'Åland Islands',
			'AL'  => 'Albania',
			'DZ'  => 'Algeria',
			'AS'  => 'American Samoa',
			'AD'  => 'Andorra',
			'AO'  => 'Angola',
			'AI'  => 'Anguilla',
			'AQ'  => 'Antarctica',
			'AG'  => 'Antigua and Barbuda',
			'AR'  => 'Argentina',
			'AM'  => 'Armenia',
			'AW'  => 'Aruba',
			'AU'  => 'Australia',
			'AT'  => 'Austria',
			'AZ'  => 'Azerbaijan',
			'BS'  => 'Bahamas',
			'BH'  => 'Bahrain',
			'BD'  => 'Bangladesh',
			'BB'  => 'Barbados',
			'BY'  => 'Belarus',
			'BE'  => 'Belgium',
			'BZ'  => 'Belize',
			'BJ'  => 'Benin',
			'BM'  => 'Bermuda',
			'BT'  => 'Bhutan',
			'BO'  => 'Bolivia, Plurinational State of',
			'BQ'  => 'Bonaire, Sint Eustatius and Saba',
			'BA'  => 'Bosnia and Herzegovina',
			'BW'  => 'Botswana',
			'BV'  => 'Bouvet Island',
			'BR'  => 'Brazil',
			'IO'  => 'British Indian Ocean Territory',
			'BN'  => 'Brunei Darussalam',
			'BG'  => 'Bulgaria',
			'BF'  => 'Burkina Faso',
			'BI'  => 'Burundi',
			'KH'  => 'Cambodia',
			'CM'  => 'Cameroon',
			'CA'  => 'Canada',
			'CV'  => 'Cape Verde',
			'KY'  => 'Cayman Islands',
			'CF'  => 'Central African Republic',
			'TD'  => 'Chad',
			'CL'  => 'Chile',
			'CN'  => 'China',
			'CX'  => 'Christmas Island',
			'CC'  => 'Cocos (Keeling) Islands',
			'CO'  => 'Colombia',
			'KM'  => 'Comoros',
			'CG'  => 'Congo',
			'CD'  => 'Congo, the Democratic Republic of the',
			'CK'  => 'Cook Islands',
			'CR'  => 'Costa Rica',
			'CI'  => 'Côte d\'Ivoire',
			'HR'  => 'Croatia',
			'CU'  => 'Cuba',
			'CW'  => 'Curaçao',
			'CY'  => 'Cyprus',
			'CZ'  => 'Czech Republic',
			'DK'  => 'Denmark',
			'DJ'  => 'Djibouti',
			'DM'  => 'Dominica',
			'DO'  => 'Dominican Republic',
			'EC'  => 'Ecuador',
			'EG'  => 'Egypt',
			'SV'  => 'El Salvador',
			'GQ'  => 'Equatorial Guinea',
			'ER'  => 'Eritrea',
			'EE'  => 'Estonia',
			'ET'  => 'Ethiopia',
			'FK'  => 'Falkland Islands (Malvinas)',
			'FO'  => 'Faroe Islands',
			'FJ'  => 'Fiji',
			'FI'  => 'Finland',
			'FR'  => 'France',
			'GF'  => 'French Guiana',
			'PF'  => 'French Polynesia',
			'TF'  => 'French Southern Territories',
			'GA'  => 'Gabon',
			'GM'  => 'Gambia',
			'GE'  => 'Georgia',
			'DE'  => 'Germany',
			'GH'  => 'Ghana',
			'GI'  => 'Gibraltar',
			'GR'  => 'Greece',
			'GL'  => 'Greenland',
			'GD'  => 'Grenada',
			'GP'  => 'Guadeloupe',
			'GU'  => 'Guam',
			'GT'  => 'Guatemala',
			'GG'  => 'Guernsey',
			'GN'  => 'Guinea',
			'GW'  => 'Guinea-Bissau',
			'GY'  => 'Guyana',
			'HT'  => 'Haiti',
			'HM'  => 'Heard Island and McDonald Islands',
			'VA'  => 'Holy See (Vatican City State)',
			'HN'  => 'Honduras',
			'HK'  => 'Hong Kong',
			'HU'  => 'Hungary',
			'IS'  => 'Iceland',
			'IN'  => 'India',
			'ID'  => 'Indonesia',
			'IR'  => 'Iran, Islamic Republic of',
			'IQ'  => 'Iraq',
			'IE'  => 'Ireland',
			'IM'  => 'Isle of Man',
			'IL'  => 'Israel',
			'IT'  => 'Italy',
			'JM'  => 'Jamaica',
			'JP'  => 'Japan',
			'JE'  => 'Jersey',
			'JO'  => 'Jordan',
			'KZ'  => 'Kazakhstan',
			'KE'  => 'Kenya',
			'KI'  => 'Kiribati',
			'KP'  => 'Korea, Democratic People\'s Republic of',
			'KR'  => 'Korea, Republic of',
			'KW'  => 'Kuwait',
			'KG'  => 'Kyrgyzstan',
			'LA'  => 'Lao People\'s Democratic Republic',
			'LV'  => 'Latvia',
			'LB'  => 'Lebanon',
			'LS'  => 'Lesotho',
			'LR'  => 'Liberia',
			'LY'  => 'Libya',
			'LI'  => 'Liechtenstein',
			'LT'  => 'Lithuania',
			'LU'  => 'Luxembourg',
			'MO'  => 'Macao',
			'MK'  => 'Macedonia, the Former Yugoslav Republic of',
			'MG'  => 'Madagascar',
			'MW'  => 'Malawi',
			'MY'  => 'Malaysia',
			'MV'  => 'Maldives',
			'ML'  => 'Mali',
			'MT'  => 'Malta',
			'MH'  => 'Marshall Islands',
			'MQ'  => 'Martinique',
			'MR'  => 'Mauritania',
			'MU'  => 'Mauritius',
			'YT'  => 'Mayotte',
			'MX'  => 'Mexico',
			'FM'  => 'Micronesia, Federated States of',
			'MD'  => 'Moldova, Republic of',
			'MC'  => 'Monaco',
			'MN'  => 'Mongolia',
			'ME'  => 'Montenegro',
			'MS'  => 'Montserrat',
			'MA'  => 'Morocco',
			'MZ'  => 'Mozambique',
			'MM'  => 'Myanmar',
			'NA'  => 'Namibia',
			'NR'  => 'Nauru',
			'NP'  => 'Nepal',
			'NL'  => 'Netherlands',
			'NC'  => 'New Caledonia',
			'NZ'  => 'New Zealand',
			'NI'  => 'Nicaragua',
			'NE'  => 'Niger',
			'NG'  => 'Nigeria',
			'NU'  => 'Niue',
			'NF'  => 'Norfolk Island',
			'MP'  => 'Northern Mariana Islands',
			'NO'  => 'Norway',
			'OM'  => 'Oman',
			'PK'  => 'Pakistan',
			'PW'  => 'Palau',
			'PS'  => 'Palestine, State of',
			'PA'  => 'Panama',
			'PG'  => 'Papua New Guinea',
			'PY'  => 'Paraguay',
			'PE'  => 'Peru',
			'PH'  => 'Philippines',
			'PN'  => 'Pitcairn',
			'PL'  => 'Poland',
			'PT'  => 'Portugal',
			'PR'  => 'Puerto Rico',
			'QA'  => 'Qatar',
			'RE'  => 'Réunion',
			'RO'  => 'Romania',
			'RU'  => 'Russian Federation',
			'RW'  => 'Rwanda',
			'BL'  => 'Saint Barthélemy',
			'SH'  => 'Saint Helena, Ascension and Tristan da Cunha',
			'KN'  => 'Saint Kitts and Nevis',
			'LC'  => 'Saint Lucia',
			'MF'  => 'Saint Martin (French part)',
			'PM'  => 'Saint Pierre and Miquelon',
			'VC'  => 'Saint Vincent and the Grenadines',
			'WS'  => 'Samoa',
			'SM'  => 'San Marino',
			'ST'  => 'Sao Tome and Principe',
			'SA'  => 'Saudi Arabia',
			'SN'  => 'Senegal',
			'RS'  => 'Serbia',
			'SC'  => 'Seychelles',
			'SL'  => 'Sierra Leone',
			'SG'  => 'Singapore',
			'SX'  => 'Sint Maarten (Dutch part)',
			'SK'  => 'Slovakia',
			'SI'  => 'Slovenia',
			'SB'  => 'Solomon Islands',
			'SO'  => 'Somalia',
			'ZA'  => 'South Africa',
			'GS'  => 'South Georgia and the South Sandwich Islands',
			'SS'  => 'South Sudan',
			'ES'  => 'Spain',
			'LK'  => 'Sri Lanka',
			'SD'  => 'Sudan',
			'SR'  => 'Suriname',
			'SJ'  => 'Svalbard and Jan Mayen',
			'SZ'  => 'Swaziland',
			'SE'  => 'Sweden',
			'CH'  => 'Switzerland',
			'SY'  => 'Syrian Arab Republic',
			'TW'  => 'Taiwan, Province of China',
			'TJ'  => 'Tajikistan',
			'TZ'  => 'Tanzania, United Republic of',
			'TH'  => 'Thailand',
			'TL'  => 'Timor-Leste',
			'TG'  => 'Togo',
			'TK'  => 'Tokelau',
			'TO'  => 'Tonga',
			'TT'  => 'Trinidad and Tobago',
			'TN'  => 'Tunisia',
			'TR'  => 'Turkey',
			'TM'  => 'Turkmenistan',
			'TC'  => 'Turks and Caicos Islands',
			'TV'  => 'Tuvalu',
			'UG'  => 'Uganda',
			'UA'  => 'Ukraine',
			'AE'  => 'United Arab Emirates',
			'GB'  => 'United Kingdom',
			'US'  => 'United States',
			'UM'  => 'United States Minor Outlying Islands',
			'UY'  => 'Uruguay',
			'UZ'  => 'Uzbekistan',
			'VU'  => 'Vanuatu',
			'VE'  => 'Venezuela, Bolivarian Republic of',
			'VN'  => 'Việt Nam',
			'VG'  => 'Virgin Islands, British',
			'VI'  => 'Virgin Islands, U.S.',
			'WF'  => 'Wallis and Futuna',
			'EH'  => 'Western Sahara',
			'YE'  => 'Yemen',
			'ZM'  => 'Zambia',
			'Z'   => 'Zimbabwe',
		);

		return apply_filters( 'rp_get_country_ISO_code', $country_ISO_code );
	}

endif;

if ( ! function_exists( 'rp_get_country_support_state' ) ) :

	/**
	 * List country support state field
	 *
	 * @return mixed|void
	 */
	function rp_get_country_support_state() {

		$list_country = array(
			'US',
			'UM',
			'GB',
			'AE',
			'IN',
		);

		return apply_filters( 'rp_country_support_state', $list_country );
	}

endif;