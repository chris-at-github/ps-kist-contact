<?php
declare(strict_types=1);

namespace Ps\Contact\Domain\Repository;

use Ps\Xo\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
 * The repository for contact countries
 */
class CountryRepository extends CategoryRepository {

	/**
	 * @param array $options
	 */
	public function findAllByLocations(array $options) {

		$countries = [];

		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_contact_domain_model_location')->createQueryBuilder();
		$statement  = $queryBuilder
			->select('*')
			->from('tx_contact_domain_model_location')
			->join(
				'tx_contact_domain_model_location',
				'sys_category',
				'sys_category',
				$queryBuilder->expr()->eq('tx_contact_domain_model_location.country', $queryBuilder->quoteIdentifier('sys_category.uid'))
			)
			->where(
				$queryBuilder->expr()->neq('country', 0)
			)
			->groupBy('country')
			->orderBy('sys_category.sorting')
			->execute();

		while($row = $statement->fetch()) {
			$countries[] = $row;
		}

		return $countries;
	}
}