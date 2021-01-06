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
class Contact extends \Ps\Xo\Domain\Model\Address {

	/**
	 * regions
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Contact\Domain\Model\Location>
	 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
	 */
	protected $locations = null;

	/**
	 * __construct
	 */
	public function __construct()	{
		$this->initStorageObjects();
	}

	/**
	 * @return void
	 */
	protected function initStorageObjects()	{
		parent::initStorageObjects();
		$this->locations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
}
