<?php

namespace Ps\Contact\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command {

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

// ...
		$io->writeln('Import successfull');
		return 0;
	}
}