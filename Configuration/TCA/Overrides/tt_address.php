<?php
defined('TYPO3_MODE') || die();

// ---------------------------------------------------------------------------------------------------------------------
// Neuer Extbase-Typ
if(isset($GLOBALS['TCA']['tt_address']['columns']['record_type']) === true) {
	$GLOBALS['TCA']['tt_address']['columns']['record_type']['config']['items'][] = ['LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_contact.record_type', 'Ps\Contact\Domain\Model\Contact'];
}

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