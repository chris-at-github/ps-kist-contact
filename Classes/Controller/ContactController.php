<?php
declare(strict_types=1);

namespace Ps\Contact\Controller;

use JeroenDesloovere\VCard\Formatter\Formatter;
use JeroenDesloovere\VCard\Formatter\VcfFormatter;
use JeroenDesloovere\VCard\Property\Email;
use JeroenDesloovere\VCard\Property\Gender;
use JeroenDesloovere\VCard\Property\Name;
use JeroenDesloovere\VCard\Property\Parameter\Type;
use JeroenDesloovere\VCard\Property\Telephone;
use JeroenDesloovere\VCard\VCard;
use Ps\Contact\Domain\Repository\ContactRepository;
use Ps\Contact\Domain\Repository\CountryRepository;
use Ps\Xo\Domain\Model\Category;
use Ps\Xo\Domain\Repository\CategoryRepository;
use Ps\Xo\Service\JsonService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Annotation\Inject;

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
	 * @var ContactRepository
	 */
	protected $contactRepository = null;

	/**
	 * @var CountryRepository
	 */
	protected $countryRepository = null;

	/**
	 * @var CategoryRepository
	 */
	protected $categoryRepository = null;

	/**
	 * @param ContactRepository $contactRepository
	 * @param CountryRepository $countryRepository
	 * @param CategoryRepository $categoryRepository
	 */
	public function __construct(ContactRepository $contactRepository, CountryRepository $countryRepository, CategoryRepository $categoryRepository) {
		$this->contactRepository = $contactRepository;
		$this->countryRepository = $countryRepository;
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listingAction() {
		$this->view->assign('contacts', $this->contactRepository->findAll([], [
			'continent.sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
			'country.sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
		]));
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
		$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('contact');
		$productLines = [];

		/** @var Category $productLine */
		foreach($this->categoryRepository->findAll(['parent' => (int) $extensionConfiguration['parentProductLineCategory']]) as $productLine) {
			$productLines[(int) $productLine->getUid()] = [
				'uid' => (int) $productLine->getUid(),
				'title' => $productLine->getTitle(),
				'countries' => $this->countryRepository->findAllByProductLine(['productLine' => (int) $productLine->getUid()])
			];
		}

		$this->view->assign('productLines', $productLines);
		$this->view->assign('record', $this->configurationManager->getContentObject()->data);
	}

	/**
	 * @return void
	 */
	public function searchAction() {
		$options = [
			'location' => []
		];

		if($this->request->hasArgument('productLine') === true) {
			$options['location']['productLine'] = $this->request->getArgument('productLine');
		}

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
		$vcard = new VCard();

		// Name + Anrede (mappen)
		$salutation = [
			'm' => LocalizationUtility::translate('LLL:EXT:xo/Resources/Private/Language/locallang_frontend.xlf:tx_xo.salutation.m', 'Xo'),
			'f' => LocalizationUtility::translate('LLL:EXT:xo/Resources/Private/Language/locallang_frontend.xlf:tx_xo.salutation.f', 'Xo'),
			'v' => '',
			'' => ''
		];

		$vcard->add(new Name($contact->getLastName(), $contact->getFirstName(), '', $salutation[$contact->getGender()]));

		// Geschlecht (bzw. mappen)
		$gender = [
			'm' => 'M',
			'f' => 'F',
			'v' => 'O',
			'' => 'U'
		];
		$vcard->add(new Gender($gender[$contact->getGender()]));

		// E-Mail geschaeftlich
		if(empty($contact->getEmail()) === false) {
			$vcard->add(new Email($contact->getEmail(), Type::work()));
		}

		// Telefon geschaeftlich
		if(empty($contact->getPhone()) === false) {
			$vcard->add(new Telephone($contact->getPhone(), Type::work()));
		}

		// Mobil geschaeftlich
		if(empty($contact->getMobile()) === false) {
			$vcard->add(new Telephone($contact->getMobile(), Type::work()));
		}

		//$vcard->add(new Org('Kist + Escherich GmbH'));

		// Dateiname
		$filename = strtolower($contact->getLastName() . '-' . $contact->getFirstName());
		$filename = str_replace(['ä', 'ö', 'ü', 'ß'], ['ae', 'oe', 'ue', 'ss'], $filename);
		$filename = preg_replace('/[^0-9a-z\-]/m', '', $filename);

		$formatter = new Formatter(new VcfFormatter(), $filename);
		$formatter->addVCard($vcard);
		$formatter->download();

		return true;
	}
}
