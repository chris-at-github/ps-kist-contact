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
 * Contact
 */
class Contact extends \Ps14\Foundation\Domain\Model\Address {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Contact\Domain\Model\Location>
	 * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
	 */
	protected $locations = null;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps14\Foundation\Domain\Model\Category>
	 */
	protected $productLine = null;

	/**
	 * @var \Ps14\Foundation\Domain\Model\Category
	 */
	protected $continent = null;

	/**
	 * @var \Ps\Contact\Domain\Model\Country
	 */
	protected $countryCategory = null;

	/**
	 * __construct
	 */
	public function __construct()	{
		$this->initStorageObjects();
		parent::__construct();
	}

	/**
	 * @return void
	 */
	protected function initStorageObjects()	{
		$this->locations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->productLine = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * @param \Ps\Contact\Domain\Model\Location $location
	 * @return void
	 */
	public function addLocation(\Ps\Contact\Domain\Model\Location $location) {
		$this->locations->attach($location);
	}

	/**
	 * @param \Ps\Contact\Domain\Model\Location $location
	 * @return void
	 */
	public function removeLocation(\Ps\Contact\Domain\Model\Location $location)	{
		$this->locations->detach($location);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Contact\Domain\Model\Location> $locations
	 */
	public function getLocations() {
		return $this->locations;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Contact\Domain\Model\Location> $locations
	 * @return void
	 */
	public function setLocations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $locations) {
		$this->locations = $locations;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage|null
	 */
	public function getProductLine(): ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage {
		return $this->productLine;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage|null $productLine
	 */
	public function setProductLine(?\TYPO3\CMS\Extbase\Persistence\ObjectStorage $productLine): void {
		$this->productLine = $productLine;
	}

	/**
	 * @return \Ps14\Foundation\Domain\Model\Category|null
	 */
	public function getContinent(): ?\Ps14\Foundation\Domain\Model\Category {
		return $this->continent;
	}

	/**
	 * @param \Ps14\Foundation\Domain\Model\Category|null $continent
	 */
	public function setContinent(?\Ps14\Foundation\Domain\Model\Category $continent): void {
		$this->continent = $continent;
	}

	/**
	 * @return \Ps14\Foundation\Domain\Model\Category|null
	 */
	public function getCountryCategory(): ?\Ps14\Foundation\Domain\Model\Category {
		return $this->countryCategory;
	}
}
