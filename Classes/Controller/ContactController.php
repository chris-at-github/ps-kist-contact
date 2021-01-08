<?php
declare(strict_types=1);

namespace Ps\Contact\Controller;

use JeroenDesloovere\VCard\VCard;
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
 * ContactController
 */
class ContactController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @TYPO3\CMS\Extbase\Annotation\Inject
	 * @var \Ps\Contact\Domain\Repository\ContactRepository
	 */
	protected $contactRepository = null;

	/**
	 * @TYPO3\CMS\Extbase\Annotation\Inject
	 * @var \Ps\Contact\Domain\Repository\CountryRepository
	 */
	protected $countryRepository = null;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('contacts', $this->contactRepository->findAll());
	}

	/**
	 * action show
	 *
	 * @param \Ps\Contact\Domain\Model\Contact $contact
	 * @return void
	 */
	public function showAction(\Ps\Contact\Domain\Model\Contact $contact) {
		$this->view->assign('contact', $contact);
	}

	/**
	 * @return void
	 */
	public function formAction() {
		$this->view->assign('countries', $this->countryRepository->findAll(['parent' => 12]));
	}

	/**
	 * @return void
	 */
	public function searchAction() {
		$options = [
			'location' => []
		];

		if($this->request->hasArgument('country') === true) {
			$options['location']['country'] = $this->request->getArgument('country');
		}

		if($this->request->hasArgument('zip') === true) {
			$options['location']['zip'] = $this->request->getArgument('zip');
		}

		$this->view->assign('contacts', $this->contactRepository->findAll($options));
	}

	/**
	 * @param \Ps\Contact\Domain\Model\Contact $contact
	 */
	public function vcardAction(\Ps\Contact\Domain\Model\Contact $contact) {
		DebuggerUtility::var_dump($contact);

		$vcard = new VCard();
		DebuggerUtility::var_dump($vcard);
	}
}
