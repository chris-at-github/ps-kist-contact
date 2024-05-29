<?php

if(defined('TYPO3') === false) {
	die('Access denied.');
}

(function () {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Contact',
		'Frontend',
		[
			\Ps\Contact\Controller\ContactController::class => 'listing'
		],
		[]
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Contact',
		'Search',
		[
			\Ps\Contact\Controller\ContactController::class => 'form, search, vcard'
		],
		[
			\Ps\Contact\Controller\ContactController::class => 'search, vcard'
		]
	);
})();