<?php

// @see: https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/6-Persistence/4-use-foreign-data-sources.html
return [
	\Ps\Contact\Domain\Model\Contact::class => [
		'tableName' => 'tt_address',
		'properties' => [
			'locations' => [
				'fieldName' => 'tx_contact_locations'
			],
		]
	],
	\Ps\Contact\Domain\Model\Country::class => [
		'tableName' => 'sys_category',
	],
];