<?php
defined('TYPO3_MODE') || die();

// ---------------------------------------------------------------------------------------------------------------------
// Neue Spalten
$tmpSysCategoryColumns = [
	'tx_contact_zip_regex' => [
		'exclude' => 1,
		'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_country.tx_contact_zip_regex',
		'config' => [
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim'
		],
	],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $tmpSysCategoryColumns);