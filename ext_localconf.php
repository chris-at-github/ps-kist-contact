<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function() {

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Contact',
		'Frontend',
		[
			\Ps\Contact\Controller\ContactController::class => 'list, show'
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
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					frontend {
						iconIdentifier = contact-plugin-frontend
						title = LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_frontend.name
						description = LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_frontend.description
						tt_content_defValues {
							CType = list
							list_type = contact_frontend
						}
					}
					search {
						iconIdentifier = contact-plugin-frontend
						title = LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_search.name
						description = LLL:EXT:contact/Resources/Private/Language/locallang_db.xlf:tx_contact_search.description
						tt_content_defValues {
							CType = list
							list_type = contact_search
						}
					}
				}
				show = *
			}
		}'
	);
	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

	$iconRegistry->registerIcon(
		'contact-plugin-frontend',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:contact/Resources/Public/Icons/user_plugin_frontend.svg']
	);
});
