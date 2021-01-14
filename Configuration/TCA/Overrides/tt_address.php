<?php
defined('TYPO3_MODE') || die();

// ---------------------------------------------------------------------------------------------------------------------
// Neuer Extbase-Typ
if(isset($GLOBALS['TCA']['tt_address']['columns']['record_type']) === true) {
	$GLOBALS['TCA']['tt_address']['columns']['record_type']['config']['items'][] = ['LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_contact.record_type', \Ps\Contact\Domain\Model\Contact::class];
}

// ---------------------------------------------------------------------------------------------------------------------
// Neue Paletten
$GLOBALS['TCA']['tt_address']['palettes']['contactName'] = [
	'showitem' => 'gender, --linebreak--, first_name, last_name,'
];

$GLOBALS['TCA']['tt_address']['palettes']['contactContact'] = [
	'showitem' => 'phone, email,'
];

$GLOBALS['TCA']['tt_address']['palettes']['contactHidden'] = [
	'showitem' => 'record_type',
	'isHiddenPalette' => 0
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
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $tmpAddressColumns);

// ---------------------------------------------------------------------------------------------------------------------
// Neue Feldzuordnungen
$GLOBALS['TCA']['tt_address']['types'][\Ps\Contact\Domain\Model\Contact::class]['showitem'] = '
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.name;contactName,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.contact;contactContact,
	,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
		--palette--;;language,
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
		--palette--;;paletteHidden, 
		--palette--;;paletteAccess,
		--palette--;;contactHidden,
';