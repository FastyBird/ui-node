<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\Bootstrap\Boot;
use FastyBird\UINode\Commands;
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
	}

}

$test_case = new ServicesTest();
$test_case->run();
