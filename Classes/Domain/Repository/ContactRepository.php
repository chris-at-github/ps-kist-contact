<?php
declare(strict_types=1);

namespace Ps\Contact\Domain\Repository;


use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/***
 *
 * This file is part of the "Contact" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Christian Pschorr <pschorr.christian@gmail.com>
 *
 ***/

/**
 * The repository for Contacts
 */
class ContactRepository extends \Ps14\Foundation\Domain\Repository\AddressRepository {

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $options
	 * @return array
	 */
	protected function getMatches($query, $options) {
		$matches = parent::getMatches($query, $options);

		if(isset($options['location']['productLine']) === true) {
			$matches[] = $query->equals('locations.productLine', $options['location']['productLine']);
		}

		if(isset($options['location']['zip']) === true) {
			$matches[] = $query->equals('locations.zip', $options['location']['zip']);
		}

		if(isset($options['location']['country']) === true) {
			$matches[] = $query->equals('locations.country', $options['location']['country']);
		}

		// NOT
		if(isset($options['not']) === true) {
			if(isset($options['not']['continent']) === true) {
				$matches['notContinent'] = $query->logicalNot($query->equals('continent', (int) $options['not']['continent']));
			}

			if(isset($options['not']['country']) === true) {
				$matches['notCountry'] = $query->logicalNot($query->equals('country', (int) $options['not']['country']));
			}
		}

		return $matches;
	}
}
