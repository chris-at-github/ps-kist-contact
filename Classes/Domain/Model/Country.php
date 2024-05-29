<?php
declare(strict_types=1);

namespace Ps\Contact\Domain\Model;


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
 * Country
 */
class Country extends \Ps14\Foundation\Domain\Model\Category {

	/**
	 * @var string
	 */
	protected $zipRegex = '';

	/**
	 * @var string
	 */
	protected $countryDescription = '';

	/**
	 * @return string $zipRegex
	 */
	public function getZipRegex() {
		return $this->zipRegex;
	}

	/**
	 * @param string $zipRegex
	 * @return void
	 */
	public function setZipRegex($zipRegex) {
		$this->zipRegex = $zipRegex;
	}

	public function getCountryDescription(): string {
		return $this->countryDescription;
	}

	public function setCountryDescription(string $countryDescription): void {
		$this->countryDescription = $countryDescription;
	}
}
