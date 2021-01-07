<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function() {

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'Contact',
		'Frontend',
		'Contact Frontend'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'Contact',
		'Search',
		'Contact Search'
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_contact_domain_model_location');
});