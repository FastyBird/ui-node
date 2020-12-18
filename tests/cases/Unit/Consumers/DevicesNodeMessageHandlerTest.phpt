<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\ModulesMetadata;
use FastyBird\ModulesMetadata\Loaders as ModulesMetadataLoaders;
use FastyBird\ModulesMetadata\Schemas as ModulesMetadataSchemas;
use FastyBird\UIModule\Sockets as UIModuleSockets;
use FastyBird\UINode\Consumers;
use Mockery;
use Nette\Utils;
use Ninjify\Nunjuck\TestCase\BaseMockeryTestCase;
use Psr\Log;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

define('DS', DIRECTORY_SEPARATOR);

/**
 * @testCase
 */
final class DevicesNodeMessageHandlerTest extends BaseMockeryTestCase
{

	/**
	 * @param mixed[] $data
	 * @param mixed[] $expected
	 * @param string $routingKey
	 * @param string $origin
	 *
	 * @dataProvider ./../../../fixtures/Consumers/deviceSuccessfulMessage.php
	 */
	public function testConsumeSuccessfulDeviceMessage(
		array $data,
		array $expected,
		string $routingKey,
		string $origin
	): void {
		$sender = Mockery::mock(UIModuleSockets\ISender::class);
		$sender
			->shouldReceive('sendEntity')
			->withArgs(function (string $destination, array $message) use ($expected): bool {
				Assert::same($expected, $message);

				return true;
			})
			->andReturn(true)
			->times(1);

		$logger = Mockery::mock(Log\LoggerInterface::class);
		$logger
			->shouldReceive('info')
			->with('[CONSUMER] Successfully consumed entity message', [
				'routing_key' => $routingKey,
				'origin'      => ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN,
				'data'        => $data,
			])
			->times(1);

		$schemaLoader = Mockery::mock(ModulesMetadataLoaders\ISchemaLoader::class);
		$schemaLoader
			->shouldReceive('load')
			->andReturn('schema-mock-content')
			->times(1)
			->getMock();

		$validator = Mockery::mock(ModulesMetadataSchemas\IValidator::class);
		$validator
			->shouldReceive('validate')
			->withArgs(function (string $payload, string $schema) use ($data): bool {
				Assert::same('schema-mock-content', $schema);
				Assert::same(Utils\Json::encode($data), $payload);

				return true;
			})
			->andReturn(Utils\ArrayHash::from($data))
			->times(1)
			->getMock();

		$consumer = new Consumers\DevicesModuleMessageHandler(
			$sender,
			$schemaLoader,
			$validator,
			$logger
		);

		$consumer->process($routingKey, $origin, Utils\Json::encode($data));
	}

	/**
	 * @param mixed[] $data
	 * @param mixed[] $expected
	 * @param string $routingKey
	 * @param string $origin
	 *
	 * @dataProvider ./../../../fixtures/Consumers/devicePropertySuccessfulMessage.php
	 */
	public function testConsumeSuccessfulDevicePropertyMessage(
		array $data,
		array $expected,
		string $routingKey,
		string $origin
	): void {
		$sender = Mockery::mock(UIModuleSockets\ISender::class);
		$sender
			->shouldReceive('sendEntity')
			->withArgs(function (string $destination, array $message) use ($expected): bool {
				Assert::same($expected, $message);

				return true;
			})
			->andReturn(true)
			->times(1);

		$logger = Mockery::mock(Log\LoggerInterface::class);
		$logger
			->shouldReceive('info')
			->with('[CONSUMER] Successfully consumed entity message', [
				'routing_key' => $routingKey,
				'origin'      => ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN,
				'data'        => $data,
			])
			->times(1);

		$schemaLoader = Mockery::mock(ModulesMetadataLoaders\ISchemaLoader::class);
		$schemaLoader
			->shouldReceive('load')
			->andReturn('schema-mock-content')
			->times(1)
			->getMock();

		$validator = Mockery::mock(ModulesMetadataSchemas\IValidator::class);
		$validator
			->shouldReceive('validate')
			->withArgs(function (string $payload, string $schema) use ($data): bool {
				Assert::same('schema-mock-content', $schema);
				Assert::same(Utils\Json::encode($data), $payload);

				return true;
			})
			->andReturn(Utils\ArrayHash::from($data))
			->times(1)
			->getMock();

		$consumer = new Consumers\DevicesModuleMessageHandler(
			$sender,
			$schemaLoader,
			$validator,
			$logger
		);

		$consumer->process($routingKey, $origin, Utils\Json::encode($data));
	}

	/**
	 * @param mixed[] $data
	 * @param mixed[] $expected
	 * @param string $routingKey
	 * @param string $origin
	 *
	 * @dataProvider ./../../../fixtures/Consumers/channelSuccessfulMessage.php
	 */
	public function testConsumeSuccessfulChannelMessage(
		array $data,
		array $expected,
		string $routingKey,
		string $origin
	): void {
		$sender = Mockery::mock(UIModuleSockets\ISender::class);
		$sender
			->shouldReceive('sendEntity')
			->withArgs(function (string $destination, array $message) use ($expected): bool {
				Assert::same($expected, $message);

				return true;
			})
			->andReturn(true)
			->times(1);

		$logger = Mockery::mock(Log\LoggerInterface::class);
		$logger
			->shouldReceive('info')
			->with('[CONSUMER] Successfully consumed entity message', [
				'routing_key' => $routingKey,
				'origin'      => ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN,
				'data'        => $data,
			])
			->times(1);

		$schemaLoader = Mockery::mock(ModulesMetadataLoaders\ISchemaLoader::class);
		$schemaLoader
			->shouldReceive('load')
			->andReturn('schema-mock-content')
			->times(1)
			->getMock();

		$validator = Mockery::mock(ModulesMetadataSchemas\IValidator::class);
		$validator
			->shouldReceive('validate')
			->withArgs(function (string $payload, string $schema) use ($data): bool {
				Assert::same('schema-mock-content', $schema);
				Assert::same(Utils\Json::encode($data), $payload);

				return true;
			})
			->andReturn(Utils\ArrayHash::from($data))
			->times(1)
			->getMock();

		$consumer = new Consumers\DevicesModuleMessageHandler(
			$sender,
			$schemaLoader,
			$validator,
			$logger
		);

		$consumer->process($routingKey, $origin, Utils\Json::encode($data));
	}

	/**
	 * @param mixed[] $data
	 * @param mixed[] $expected
	 * @param string $routingKey
	 * @param string $origin
	 *
	 * @dataProvider ./../../../fixtures/Consumers/channelPropertySuccessfulMessage.php
	 */
	public function testConsumeSuccessfulChannelPropertyMessage(
		array $data,
		array $expected,
		string $routingKey,
		string $origin
	): void {
		$sender = Mockery::mock(UIModuleSockets\ISender::class);
		$sender
			->shouldReceive('sendEntity')
			->withArgs(function (string $destination, array $message) use ($expected): bool {
				Assert::same($expected, $message);

				return true;
			})
			->andReturn(true)
			->times(1);

		$logger = Mockery::mock(Log\LoggerInterface::class);
		$logger
			->shouldReceive('info')
			->with('[CONSUMER] Successfully consumed entity message', [
				'routing_key' => $routingKey,
				'origin'      => ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN,
				'data'        => $data,
			])
			->times(1);

		$schemaLoader = Mockery::mock(ModulesMetadataLoaders\ISchemaLoader::class);
		$schemaLoader
			->shouldReceive('load')
			->andReturn('schema-mock-content')
			->times(1)
			->getMock();

		$validator = Mockery::mock(ModulesMetadataSchemas\IValidator::class);
		$validator
			->shouldReceive('validate')
			->withArgs(function (string $payload, string $schema) use ($data): bool {
				Assert::same('schema-mock-content', $schema);
				Assert::same(Utils\Json::encode($data), $payload);

				return true;
			})
			->andReturn(Utils\ArrayHash::from($data))
			->times(1)
			->getMock();

		$consumer = new Consumers\DevicesModuleMessageHandler(
			$sender,
			$schemaLoader,
			$validator,
			$logger
		);

		$consumer->process($routingKey, $origin, Utils\Json::encode($data));
	}

}

$test_case = new DevicesNodeMessageHandlerTest();
$test_case->run();
