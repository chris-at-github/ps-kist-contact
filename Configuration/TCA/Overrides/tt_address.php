<?php
defined('TYPO3_MODE') || die();

$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('contact');

// ---------------------------------------------------------------------------------------------------------------------
// Neuer Extbase-Typ
if(isset($GLOBALS['TCA']['tt_address']['columns']['record_type']) === true) {
	$GLOBALS['TCA']['tt_address']['columns']['record_type']['config']['items'][] = [
		'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_contact.record_type', \Ps\Contact\Domain\Model\Contact::class
	];
}

// ---------------------------------------------------------------------------------------------------------------------
// Neue Paletten
$GLOBALS['TCA']['tt_address']['palettes']['contactName'] = [
	'showitem' => 'gender, --linebreak--, name, --linebreak--, first_name, last_name, --linebreak--, company,'
];

$GLOBALS['TCA']['tt_address']['palettes']['contactDescription'] = [
	'showitem' => 'description'
];

$GLOBALS['TCA']['tt_address']['palettes']['contactContact'] = [
	'showitem' => 'phone, email, --linebreak--, www, '
];

$GLOBALS['TCA']['tt_address']['palettes']['contactRelation'] = [
	'showitem' => 'tx_contact_product_line, --linebreak--, tx_contact_continent, --linebreak--, tx_contact_country,'
];

// ---------------------------------------------------------------------------------------------------------------------
// Neue Spalten
$tmpAddressColumns = [
	'tx_contact_locations' => [
		'exclude' => 1,
		'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_contact.locations',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'tx_contact_domain_model_location',
			'foreign_field' => 'contact',
			'maxitems' => 9999,
			'appearance' => [
				'collapseAll' => 1,
				'expandSingle' => 1,
			],
		],
	],
	'tx_contact_product_line' => [
		'exclude' => true,
		'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tt_address.tx_contact_product_line',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectCheckBox',
			'size' => 5,
			'foreign_table' => 'sys_category',
			'MM' => 'sys_category_record_mm',
			'MM_match_fields' => [
				'fieldname' => 'tx_contact_product_line',
				'tablenames' => 'tt_address',
			],
			'MM_opposite_field' => 'items',
			'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0) and sys_category.parent = ' . (int) $extensionConfiguration['parentProductLineCategory'] . ' ORDER BY sys_category.sorting ASC',
		],
	],
	'tx_contact_continent' => [
		'exclude' => true,
		'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tt_address.tx_contact_continent',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => [
				['', 0],
			],
			'size' => 1,
			'maxitems' => 1,
			'foreign_table' => 'sys_category',
			'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0) and sys_category.parent = ' . (int) $extensionConfiguration['parentContinentCategory'] . ' ORDER BY sys_category.sorting ASC',
		],
	],
	'tx_contact_country' => [
		'exclude' => true,
		'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tt_address.tx_contact_country',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => [
				['', 0],
			],
			'size' => 1,
			'maxitems' => 1,
			'foreign_table' => 'sys_category',
			'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0) and sys_category.parent = ' . (int) $extensionConfiguration['parentCountryCategory'] . ' ORDER BY sys_category.sorting ASC',
		],
	],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $tmpAddressColumns);

// ---------------------------------------------------------------------------------------------------------------------
// Neue Feldzuordnungen
$GLOBALS['TCA']['tt_address']['types'][\Ps\Contact\Domain\Model\Contact::class]['showitem'] = '
		record_type,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.name;contactName,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.contact;contactContact,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.relation;contactRelation,
		--palette--;;contactDescription,
	,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
		--palette--;;language,
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
		--palette--;;paletteHidden, 
		--palette--;;paletteAccess,
';

$GLOBALS['TCA']['tt_address']['types'][\Ps\Contact\Domain\Model\Contact::class]['columnsOverrides']['www']['config'] = [
	'size' => 55,
];

$GLOBALS['TCA']['tt_address']['types'][\Ps\Contact\Domain\Model\Contact::class]['columnsOverrides']['name']['label'] = 'Name (intern)';
$GLOBALS['TCA']['tt_address']['types'][\Ps\Contact\Domain\Model\Contact::class]['columnsOverrides']['name'] = [
	'config' => [
		'readOnly' => false,
	]
];

$GLOBALS['TCA']['tt_address']['types'][\Ps\Contact\Domain\Model\Contact::class]['columnsOverrides']['description'] = [
	'config' => [
		'richtextConfiguration' => 'xoDefault',
	]
];