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
 * Location
 */
class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * zip
     * 
     * @var string
     */
    protected $zip = '';

    /**
     * contact
     * 
     * @var \Ps\Contact\Domain\Model\Contact
     */
    protected $contact = null;

    /**
     * country
     * 
     * @var \Ps\Contact\Domain\Model\Country
     */
    protected $country = null;

	/**
	 * country
	 *
	 * @var \Ps14\Foundation\Domain\Model\Category
	 */
	protected $productLine = null;

    /**
     * Returns the zip
     * 
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     * 
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the contact
     * 
     * @return \Ps\Contact\Domain\Model\Contact $contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Sets the contact
     * 
     * @param \Ps\Contact\Domain\Model\Contact $contact
     * @return void
     */
    public function setContact(\Ps\Contact\Domain\Model\Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Returns the country
     * 
     * @return \Ps\Contact\Domain\Model\Country $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     * 
     * @param \Ps\Contact\Domain\Model\Country $country
     * @return void
     */
    public function setCountry(\Ps\Contact\Domain\Model\Country $country)
    {
        $this->country = $country;
    }

	/**
	 * @return \Ps14\Foundation\Domain\Model\Category|null
	 */
	public function getProductLine(): ?\Ps14\Foundation\Domain\Model\Category {
		return $this->productLine;
	}

	/**
	 * @param \Ps14\Foundation\Domain\Model\Category|null $productLine
	 */
	public function setProductLine(?\Ps14\Foundation\Domain\Model\Category $productLine): void {
		$this->productLine = $productLine;
	}
}
