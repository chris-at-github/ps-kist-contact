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
class Country extends \Ps\Xo\Domain\Model\Category {

	/**
	 * zipRegex
	 *
	 * @var string
	 */
	protected $zipRegex = '';

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
}
