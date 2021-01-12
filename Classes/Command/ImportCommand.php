<?php

namespace Ps\Contact\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\Search\FileSearchDemand;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class ImportCommand extends Command {

	/**
	 * @var array
	 */
	protected $contacts = [];

	/**
	 * @var array
	 */
	protected $countries = [];

	/**
	 * Configure the command by defining the name, options and arguments
	 */
	protected function configure() {
		$this->setDescription('Import all contacts form a csv file');
	}

	/**
	 * Executes the command for showing sys_log entries
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int error code
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$io = new SymfonyStyle($input, $output);
		$io->title($this->getDescription());


		foreach($this->getFiles() as $file) {
			$this->importFile($file);
		}

		return 0;
	}

	/**
	 * @param string $identifier
	 * @return array|null
	 */
	protected function findContactByIdentifier(string $identifier) {
		if(isset($this->contacts[$identifier]) === false) {
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
			$statement = $queryBuilder
				->select('uid')
				->from('tt_address')
				->where(
					$queryBuilder->expr()->eq('email', $queryBuilder->createNamedParameter($identifier, \PDO::PARAM_STR)),
					$queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(115, \PDO::PARAM_INT))
				)
				->execute();

			$uid = $statement->fetchColumn(0);

			if($uid !== false) {
				$this->contacts[$identifier] = (int) $uid;
			}
		}

		if(isset($this->contacts[$identifier]) === true) {
			return $this->contacts[$identifier];
		}

		return null;
	}

	/**
	 * @param string $identifier
	 * @return array|null
	 */
	protected function findCountryByIdentifier(string $identifier) {
		if(isset($this->countries[$identifier]) === false) {
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
			$statement = $queryBuilder
				->select('uid')
				->from('sys_category')
				->where(
					$queryBuilder->expr()->eq('title', $queryBuilder->createNamedParameter($identifier, \PDO::PARAM_STR)),
					$queryBuilder->expr()->eq('parent', $queryBuilder->createNamedParameter(12, \PDO::PARAM_INT))
				)
				->execute();

			$uid = $statement->fetchColumn(0);

			if($uid !== false) {
				$this->countries[$identifier] = (int) $uid;
			}
		}

		if(isset($this->countries[$identifier]) === true) {
			return $this->countries[$identifier];
		}

		return null;
	}

	/**
	 * @see: https://stackoverflow.com/a/45097576
	 * @param string $value
	 * @return string
	 */
	protected function autoConvertEncoding($value) {
		// detect UTF-8
		if(preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $value)) {
			return $value;
		}

		// detect WINDOWS-1250
		if(preg_match('#[\x7F-\x9F\xBC]#', $value)) {
			return iconv('WINDOWS-1250', 'UTF-8', $value);
		}

		// assume ISO-8859-2
		return iconv('ISO-8859-2', 'UTF-8', $value);
	}

	/**
	 * @param File $file
	 */
	protected function importFile(File $file) {

		GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_contact_domain_model_location')->truncate('tx_contact_domain_model_location');

		$index = [];
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_contact_domain_model_location');

		$rows = GeneralUtility::trimExplode("\n", $file->getContents());
		foreach($rows as $i => $row) {
			$data = str_getcsv($this->autoConvertEncoding($row), ',');

			if($i === 0) {
				foreach($data as $key => $value) {
					$index[strtolower(trim($value))] = (int) $key;
				}

			} else {
				$contact = $this->findContactByIdentifier(trim($data[$index['e-mail']]));
				$country = $this->findCountryByIdentifier(trim($data[$index['land']]));

				if($contact === null) {
					$contact = $this->importContact($data, $index);
				}

				if(empty($contact) === false && empty($country) === false) {
					$queryBuilder
						->insert('tx_contact_domain_model_location')
						->values([
							'pid' => (int) 115,
							'tstamp' => time(),
							'crdate' => time(),
							'cruser_id' => 0,
							'sorting' => $row,
							'zip' => trim($data[$index['plz']]),
							'contact' => $contact,
							'country' => $country
						])
						->execute();
				}
			}
		}
	}

	/**
	 * @param array $data
	 * @param array $index
	 * @return int
	 */
	protected function importContact(array $data, array $index) {

		$gender = '';

		if(trim($data[$index['anrede']]) === 'Herr') {
			$gender = 'm';

		} elseif(trim($data[$index['anrede']]) === 'Frau') {
			$gender = 'f';
		}

		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
		$queryBuilder
			->insert('tt_address')
			->values([
				'pid' => (int) 115,
				'tstamp' => time(),
				'crdate' => time(),
				'cruser_id' => 0,
				'sorting' => 999,
				'gender' => $gender,
				'name' =>  trim($data[$index['vorname']]) . ' ' . trim($data[$index['nachname']]),
				'first_name' => trim($data[$index['vorname']]),
				'last_name' => trim($data[$index['nachname']]),
				'phone' => trim($data[$index['telefon']]),
				'email' => trim($data[$index['e-mail']])
			])
			->execute();

		$uid = $queryBuilder->getConnection()->lastInsertId();

		if(empty($uid) === false) {
			$this->contacts[trim($data[$index['email']])] = $uid;
		}

		return $uid;
	}

	/**
	 * @return Folder
	 */
	protected function getStorageFolder() {
		return GeneralUtility::makeInstance(ResourceFactory::class)->getFolderObjectFromCombinedIdentifier('1:/import/contact');
	}

	/**
	 * @return \TYPO3\CMS\Core\Resource\File[]
	 */
	protected function getFiles() {
		$folder = $this->getStorageFolder();
		$files = [];

		foreach($folder->getFiles(0, 1) as $file) {
			if($file->getExtension() === 'csv') {
				$files[] = $file;
			}
		}

		return $files;
	}


}