<?php
defined('TYPO3_MODE') || die();


if (!isset($GLOBALS['TCA']['sys_category']['ctrl']['type'])) {
    // no type field defined, so we define it here. This will only happen the first time the extension is installed!!
    $GLOBALS['TCA']['sys_category']['ctrl']['type'] = 'tx_extbase_type';
    $tempColumnstx_contact_sys_category = [];
    $tempColumnstx_contact_sys_category[$GLOBALS['TCA']['sys_category']['ctrl']['type']] = [
        'exclude' => true,
        'label'   => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact.tx_extbase_type',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['',''],
                ['Country','Tx_Contact_Country']
            ],
            'default' => 'Tx_Contact_Country',
            'size' => 1,
            'maxitems' => 1,
        ]
    ];
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category', $tempColumnstx_contact_sys_category);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    $GLOBALS['TCA']['sys_category']['ctrl']['type'],
    '',
    'after:' . $GLOBALS['TCA']['sys_category']['ctrl']['label']
);




$tmp_contact_columns = [


    'tx_contact_zip_regex' => [
        'exclude' => true,
        'label' => 'LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_country.tx_contact_zip_regex',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim'
        ],
    ],

];


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category',$tmp_contact_columns);


/* inherit and extend the show items from the parent class */

if (isset($GLOBALS['TCA']['sys_category']['types']['1']['showitem'])) {
    $GLOBALS['TCA']['sys_category']['types']['Tx_Contact_Country']['showitem'] = $GLOBALS['TCA']['sys_category']['types']['1']['showitem'];
} elseif(is_array($GLOBALS['TCA']['sys_category']['types'])) {
    // use first entry in types array
    $sys_category_type_definition = reset($GLOBALS['TCA']['sys_category']['types']);
    $GLOBALS['TCA']['sys_category']['types']['Tx_Contact_Country']['showitem'] = $sys_category_type_definition['showitem'];
} else {
    $GLOBALS['TCA']['sys_category']['types']['Tx_Contact_Country']['showitem'] = '';
}
$GLOBALS['TCA']['sys_category']['types']['Tx_Contact_Country']['showitem'] .= ',--div--;LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_domain_model_country,';
$GLOBALS['TCA']['sys_category']['types']['Tx_Contact_Country']['showitem'] .= 'tx_contact_zip_regex';


$GLOBALS['TCA']['sys_category']['columns'][$GLOBALS['TCA']['sys_category']['ctrl']['type']]['config']['items'][] = ['LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:sys_category.tx_extbase_type.Tx_Contact_Country','Tx_Contact_Country'];
