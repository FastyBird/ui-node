<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\Bootstrap\Boot;
use FastyBird\UINode\Commands;
use FastyBird\UINode\Consumers;
use FastyBird\UINode\Events;
use FastyBird\UINode\Sockets;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ServicesTest extends BaseTestCase
{

	public function testServicesRegistration(): void
	{
		$configurator = Boot\Bootstrap::boot();
		$configurator->addParameters([
			'database' => [
				'driver' => 'pdo_sqlite',
			],
		]);

		$container = $configurator->createContainer();

		Assert::notNull($container->getByType(Commands\InitializeCommand::class));

		Assert::notNull($container->getByType(Consumers\AuthNodeMessageHandler::class));
		Assert::notNull($container->getByType(Consumers\DevicesNodeMessageHandler::class));
		Assert::notNull($container->getByType(Consumers\TriggersNodeMessageHandler::class));

		Assert::notNull($container->getByType(Events\AfterConsumeHandler::class));
		Assert::notNull($container->getByType(Events\ServerBeforeStartHandler::class));
		Assert::notNull($container->getByType(Events\ServerAfterStartHandler::class));

		Assert::notNull($container->getByType(Sockets\Publisher::class));
	}

}

$test_case = new ServicesTest();
$test_case->run();
