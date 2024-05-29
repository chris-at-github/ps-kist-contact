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
use JeroenDesloovere\VCard\Property\Title;
use JeroenDesloovere\VCard\VCard;
use Ps\Contact\Domain\Model\Contact;
use Ps\Contact\Domain\Repository\ContactRepository;
use Ps\Contact\Domain\Repository\CountryRepository;
use Ps14\Foundation\Domain\Model\Category;
use Ps14\Foundation\Domain\Repository\CategoryRepository;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function listingAction() {
		$this->view->assign('record', $this->configurationManager->getContentObject()->data);
		$this->view->assign('contacts', $this->contactRepository->findAllByOption([
			'not' => [
				'continent' => 0,
				'countryCategory' => 0,
			]
		], [
			'continent.sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
			'countryCategory.sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
		]));

		return $this->htmlResponse();
	}

	/**
	 * @param Contact $contact
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function showAction(Contact $contact) {
		$this->view->assign('contact', $contact);
		return $this->htmlResponse();
	}

	/**
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function formAction() {
		$productLines = [];

		/** @var Category $productLineMain */
		$productLineMain = $this->categoryRepository->findByOption(['identifier' => 'contact-product-lines']);

		/** @var Category $productLine */
		foreach($this->categoryRepository->findAllByOption(['parent' => (int) $productLineMain->getUid()]) as $productLine) {
			$productLines[(int) $productLine->getUid()] = [
				'uid' => (int) $productLine->getUid(),
				'title' => $productLine->getTitle(),
				'countries' => $this->countryRepository->findAllByProductLine(['productLine' => (int) $productLine->getUid()])
			];
		}

		$this->view->assign('productLines', $productLines);
		$this->view->assign('record', $this->request->getAttribute('currentContentObject')->data);
		return $this->htmlResponse();
	}

	/**
	 * @return \Psr\Http\Message\ResponseInterface
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

		$this->view->assign('contacts', $this->contactRepository->findAllByOption($options));
		return $this->htmlResponse();
	}

	/**
	 * @param Contact $contact
	 * @return boolean
	 */
	public function vcardAction(\Ps\Contact\Domain\Model\Contact $contact) {
		$vcard = new VCard();

		// Name + Anrede (mappen)
		$salutation = [
			'm' => LocalizationUtility::translate('LLL:EXT:ps14_foundation/Resources/Private/Language/locallang_frontend.xlf:salutation.m'),
			'f' => LocalizationUtility::translate('LLL:EXT:ps14_foundation/Resources/Private/Language/locallang_frontend.xlf:salutation.f'),
			'v' => '',
			'' => ''
		];

		if(empty($contact->getLastName()) === false || empty($contact->getFirstName()) === false) {
			$vcard->add(new Name($contact->getLastName(), $contact->getFirstName(), '', $salutation[$contact->getGender()]));
		} else {
			$vcard->add(new Name('', '', '', $salutation[$contact->getGender()]));
		}

		if(empty($contact->getCompany()) === false) {
			$vcard->add(new Title($contact->getCompany()));
		}

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

		// Dateiname
		if(empty($contact->getLastName()) === false || empty($contact->getFirstName()) === false) {
			$filename = strtolower($contact->getLastName() . '-' . $contact->getFirstName());

		} elseif(empty($contact->getCompany()) === false) {
			$filename = $contact->getCompany();
		}

		$filename = str_replace(['ä', 'ö', 'ü', 'ß'], ['ae', 'oe', 'ue', 'ss'], $filename);
		$filename = preg_replace('/[^0-9a-z\-]/m', '', $filename);
		$filename = trim($filename, ' -');

		$formatter = new Formatter(new VcfFormatter(), $filename);
		$formatter->addVCard($vcard);

		$response = $this->responseFactory->createResponse();
		$response = $response->withBody($this->streamFactory->createStream((string) $formatter->getContent()));

		foreach($formatter->getHeaders() as $name => $value) {
			$response = $response->withHeader($name, (string) $value);
		}

		return $response;
	}
}
