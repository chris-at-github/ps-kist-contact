<?php
declare(strict_types=1);

namespace Ps\Contact\Controller;


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
	 * contactRepository
	 *
	 * @var \Ps\Contact\Domain\Repository\ContactRepository
	 */
	protected $contactRepository = null;

	/**
	 * @param \Ps\Contact\Domain\Repository\ContactRepository $contactRepository
	 */
	public function injectContactRepository(\Ps\Contact\Domain\Repository\ContactRepository $contactRepository) {
		$this->contactRepository = $contactRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$contacts = $this->contactRepository->findAll(['location' => ['zip' => '79215', 'country' => 13]]);
		$this->view->assign('contacts', $contacts);
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
}
