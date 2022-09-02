<?php

$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('contact');

return [
	'ctrl' => [
		'title' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_location',
		'label' => 'zip',
		'label_alt' => 'country',
		'label_alt_force' => true,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'versioningWS' => true,
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => 'zip',
		'iconfile' => 'EXT:contact/Resources/Public/Icons/tx_contact_domain_model_location.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, zip, contact, country',
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, zip, contact, country, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					]
				],
				'default' => 0,
			],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'default' => 0,
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_contact_domain_model_location',
				'foreign_table_where' => 'AND {#tx_contact_domain_model_location}.{#pid}=###CURRENT_PID### AND {#tx_contact_domain_model_location}.{#sys_language_uid} IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		't3ver_label' => [
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			],
		],
		'hidden' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
			'config' => [
				'type' => 'check',
				'renderType' => 'checkboxToggle',
				'items' => [
					[
						0 => '',
						1 => '',
						'invertStateDisplay' => true
					]
				],
			],
		],
		'starttime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
				'default' => 0,
				'behaviour' => [
					'allowLanguageSynchronization' => true
				]
			],
		],
		'endtime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
				'default' => 0,
				'range' => [
					'upper' => mktime(0, 0, 0, 1, 1, 2038)
				],
				'behaviour' => [
					'allowLanguageSynchronization' => true
				]
			],
		],
		'zip' => [
			'exclude' => true,
			'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_location.zip',
			'config' => [
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			],
		],
		'contact' => [
			'exclude' => true,
			'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_location.contact',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tt_address',
				'foreign_table_where' => ' AND tt_address.sys_language_uid IN (-1, 0) and tt_address.pid = ' . (int) $extensionConfiguration['uidContactFolder'] . ' ORDER BY tt_address.sorting ASC',
				'default' => 0,
				'minitems' => 0,
				'maxitems' => 1,
				'size' => 1,
			],
		],
		'country' => [
			'exclude' => true,
			'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_location.country',
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
	],
];
