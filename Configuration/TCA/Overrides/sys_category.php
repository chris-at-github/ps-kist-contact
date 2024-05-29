<?php

(function() {

	// -------------------------------------------------------------------------------------------------------------------
	// Weitere Felder in Categories
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', [
		'tx_contact_zip_regex' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_country.tx_contact_zip_regex',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'tx_contact_country_description' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_country.tx_contact_country_description',
			'config' => [
				'type' => 'text',
				'enableRichtext' => true,
				'richtextConfiguration' => 'ps14Minimal',
				'fieldControl' => [
					'fullScreenRichtext' => [
						'disabled' => false,
					],
				],
				'eval' => 'trim',
			],
		],
	]);

	// -------------------------------------------------------------------------------------------------------------------
	// Neue Felder einsortieren
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', ', --linebreak--, tx_contact_zip_regex', '', 'after:title');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category', ', --linebreak--, tx_contact_country_description', '', 'after:parent');
})();