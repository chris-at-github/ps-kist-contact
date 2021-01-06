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
class Country extends \Ps\Xo\Domain\Model\Category
{

    /**
     * txContactZipRegex
     * 
     * @var string
     */
    protected $txContactZipRegex = '';

    /**
     * Returns the txContactZipRegex
     * 
     * @return string $txContactZipRegex
     */
    public function getTxContactZipRegex()
    {
        return $this->txContactZipRegex;
    }

    /**
     * Sets the txContactZipRegex
     * 
     * @param string $txContactZipRegex
     * @return void
     */
    public function setTxContactZipRegex($txContactZipRegex)
    {
        $this->txContactZipRegex = $txContactZipRegex;
    }
}
