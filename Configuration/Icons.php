<?php

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
	'contact-frontend' => [
		'provider' => SvgIconProvider::class,
		'source' => 'EXT:contact/Resources/Public/Icons/contact-frontend.svg',
	],
	'contact-search' => [
		'provider' => SvgIconProvider::class,
		'source' => 'EXT:contact/Resources/Public/Icons/contact-search.svg',
	],
	'contact-location' => [
		'provider' => SvgIconProvider::class,
		'source' => 'EXT:contact/Resources/Public/Icons/contact-location.svg',
	],
];