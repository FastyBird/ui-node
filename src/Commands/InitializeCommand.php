<?php declare(strict_types = 1);

/**
 * InitializeCommand.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Commands
 * @since          0.1.0
 *
 * @date           08.08.20
 */

namespace FastyBird\UINode\Commands;

use FastyBird\Database;
use RuntimeException;
use Symfony\Component\Console;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Component\Console\Style;
use Throwable;

/**
 * Node initialize command
 *
 * @package        FastyBird:UINode!
 * @subpackage     Commands
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class InitializeCommand extends Console\Command\Command
{

	/** @var Database\Helpers\Database */
	private Database\Helpers\Database $database;

	public function __construct(
		Database\Helpers\Database $database,
		?string $name = null
	) {
		parent::__construct($name);

		$this->database = $database;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function configure(): void
	{
		$this
			->setName('fb:initialize')
			->addOption('noconfirm', null, Input\InputOption::VALUE_NONE, 'do not ask for any confirmation')
			->setDescription('Initialize node.');
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
	{
		$symfonyApp = $this->getApplication();

		if ($symfonyApp === null) {
			return 1;
		}

		$io = new Style\SymfonyStyle($input, $output);

		$io->title('FB UI node - initialization');

		$io->note('This action will create|update node database structure.');

		/** @var bool $continue */
		$continue = $io->ask('Would you like to continue?', 'n', function ($answer): bool {
			if (!in_array($answer, ['y', 'Y', 'n', 'N'], true)) {
				throw new RuntimeException('You must type Y or N');
			}

			return in_array($answer, ['y', 'Y'], true);
		});

		if (!$continue) {
			return 0;
		}

		$io->section('Checking database connection');

		try {
			if (!$this->database->ping()) {
				$io->error('Connection to the database could not be established. Check configuration.');

				return 1;
			}
		} catch (Throwable $ex) {
			$io->error('Something went wrong, initialization could not be finished.');

			return 1;
		}

		$io->section('Preparing node database');

		$databaseCmd = $symfonyApp->find('orm:schema-tool:update');

		$result = $databaseCmd->run(new Input\ArrayInput([
			'--force' => true,
		]), $output);

		if ($result !== 0) {
			$io->error('Something went wrong, initialization could not be finished.');

			return 1;
		}

		$databaseProxiesCmd = $symfonyApp->find('orm:generate-proxies');

		$result = $databaseProxiesCmd->run(new Input\ArrayInput([
			'--quiet' => true,
		]), $output);

		if ($result !== 0) {
			$io->error('Something went wrong, initialization could not be finished.');

			return 1;
		}

		$io->newLine(3);

		$io->success('This node has been successfully initialized and can be now started.');

		return 0;
	}

}
