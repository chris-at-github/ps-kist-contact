<?php
declare(strict_types=1);

namespace Ps\Contact\Domain\Repository;

use \Ps14\Foundation\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
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
	 * @var LanguageAspect
	 */
	protected $languageAspect = null;

	/**
	 * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected function getFrontend() {
		return $GLOBALS['TSFE'];
	}

	/**
	 * @return LanguageAspect
	 */
	protected function getLanguageAspect() {
		if($this->languageAspect === null) {
			$this->languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
		}

		return $this->languageAspect;
	}

	/**
	 * @param array $options
	 */
	public function findAllByLocations(array $options) {

		$countries = [];

		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_contact_domain_model_location')->createQueryBuilder();
		$statement  = $queryBuilder
			->select('sys_category.uid', 'sys_category.title', 'sys_category.tx_contact_zip_regex AS zipRegex', 'tx_contact_domain_model_location.product_line AS productLine')
			->from('tx_contact_domain_model_location')
			->join(
				'tx_contact_domain_model_location',
				'sys_category',
				'sys_category',
				$queryBuilder->expr()->eq('tx_contact_domain_model_location.country', $queryBuilder->quoteIdentifier('sys_category.uid'))
			)
			->where(
				$queryBuilder->expr()->neq('country', 0),
				$queryBuilder->expr()->neq('product_line', 0),
				$queryBuilder->expr()->in('sys_category.sys_language_uid', [0, -1])
			)
			->groupBy('country')
			->orderBy('sys_category.sorting')
			->execute();

		while($row = $statement->fetch()) {

			if(empty($row) === false && (int) $row['sys_language_uid'] !== $this->getLanguageAspect()->getContentId()) {
				$row = $this->getFrontend()->sys_page->getRecordOverlay(
					'sys_category',
					$row,
					$this->getLanguageAspect()
				);

				if($row === null) {
					continue;
				}
			}

			$countries[] = $row;
		}

		return $countries;
	}

	/**
	 * @param array $options
	 */
	public function findAllByProductLine(array $options) {

		$countries = [];
		$i = 1;

		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_contact_domain_model_location')->createQueryBuilder();
		$statement  = $queryBuilder
			->select('sys_category.*', 'sys_category.tx_contact_zip_regex AS zipRegex', 'tx_contact_domain_model_location.zip AS zip', 'tx_contact_domain_model_location.product_line AS productLine')
			->addSelectLiteral(
				$queryBuilder->expr()->max('tx_contact_domain_model_location.zip', 'isRegex')
			)
			->from('tx_contact_domain_model_location')
			->join(
				'tx_contact_domain_model_location',
				'sys_category',
				'sys_category',
				$queryBuilder->expr()->eq('tx_contact_domain_model_location.country', $queryBuilder->quoteIdentifier('sys_category.uid'))
			)
			->where(
				$queryBuilder->expr()->neq('country', 0),
				$queryBuilder->expr()->eq('product_line', $options['productLine']),
				$queryBuilder->expr()->in('sys_category.sys_language_uid', [0, -1])
			)
			->groupBy('sys_category.uid')
			->orderBy('sys_category.title')
			->addOrderBy('tx_contact_domain_model_location.zip', 'DESC')
			->execute();

		while($row = $statement->fetch()) {
			if(empty($row['isRegex']) === true) {
				$row['zipRegex'] = '';
			}

			$row['sorting'] = $i++;

			if(empty($row) === false && (int) $row['sys_language_uid'] !== $this->getLanguageAspect()->getContentId()) {
				$row = $this->getFrontend()->sys_page->getRecordOverlay(
					'sys_category',
					$row,
					$this->getLanguageAspect()
				);

				if($row === null) {
					continue;
				}
			}

			$countries[(int) $row['uid']] = $row;
		}

		return $countries;
	}
}