<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function() {

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Contact',
		'Frontend',
		[
			\Ps\Contact\Controller\ContactController::class => 'listing'
		],
		// non-cacheable actions
		[
			\Ps\Contact\Controller\ContactController::class => ''
		]
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Contact',
		'Search',
		[
			\Ps\Contact\Controller\ContactController::class => 'form, search, vcard'
		],
		// non-cacheable actions
		[
			\Ps\Contact\Controller\ContactController::class => 'search, vcard'
		]
	);

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'<INCLUDE_TYPOSCRIPT: source="FILE:EXT:contact/Configuration/TSConfig/Page.t3s">'
	);

	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

	$iconRegistry->registerIcon(
		'contact-plugin-frontend',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:contact/Resources/Public/Icons/user_plugin_frontend.svg']
	);
});
