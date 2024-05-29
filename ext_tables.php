<?php

if(defined('TYPO3') === false) {
	die('Access denied.');
}

(function () {

	// Frontend | Listing
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'Contact',
		'Frontend',
		'LLL:EXT:contact/Resources/Private/Language/locallang_plugin.xlf:frontend.title',
		'foundation-address-record'
	);

	// Search
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'Contact',
		'Search',
		'LLL:EXT:contact/Resources/Private/Language/locallang_plugin.xlf:frontend.search',
		'foundation-address-record'
	);
})();