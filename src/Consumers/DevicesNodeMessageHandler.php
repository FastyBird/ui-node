<?php declare(strict_types = 1);

/**
 * DevicesNodeMessageHandler.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 * @since          0.1.0
 *
 * @date           15.08.20
 */

namespace FastyBird\UINode\Consumers;

use FastyBird\ModulesMetadata;
use FastyBird\ModulesMetadata\Loaders as ModulesMetadataLoaders;
use FastyBird\ModulesMetadata\Schemas as ModulesMetadataSchemas;
use FastyBird\UIModule\Sockets as UIModuleSockets;
use FastyBird\UINode;
use FastyBird\UINode\Exceptions;
use Psr\Log;

/**
 * Devices node message consumer
 *
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DevicesNodeMessageHandler extends MessageHandler
{

	use TDataTransformer;

	/** @var UIModuleSockets\ISender */
	private $sender;

	public function __construct(
		UIModuleSockets\ISender $sender,
		ModulesMetadataLoaders\ISchemaLoader $schemaLoader,
		ModulesMetadataSchemas\IValidator $validator,
		?Log\LoggerInterface $logger = null
	) {
		parent::__construct($schemaLoader, $validator, $logger);

		$this->sender = $sender;
	}

	/**
	 * {@inheritDoc}
	 */
	public function process(
		string $routingKey,
		string $origin,
		string $payload
	): bool {
		$message = $this->parseMessage($routingKey, $origin, $payload);

		if ($message === null) {
			return true;
		}

		switch ($routingKey) {
			// Devices
			case UINode\Constants::RABBIT_MQ_DEVICES_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_DEVICES_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_DEVICES_DELETED_ENTITY_ROUTING_KEY:
				// Devices configuration
			case UINode\Constants::RABBIT_MQ_DEVICES_CONFIGURATION_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_DEVICES_CONFIGURATION_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_DEVICES_CONFIGURATION_DELETED_ENTITY_ROUTING_KEY:
				// Devices properties
			case UINode\Constants::RABBIT_MQ_DEVICES_PROPERTY_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_DEVICES_PROPERTY_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_DEVICES_PROPERTY_DELETED_ENTITY_ROUTING_KEY:
				// Channels
			case UINode\Constants::RABBIT_MQ_CHANNELS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_CHANNELS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_CHANNELS_DELETED_ENTITY_ROUTING_KEY:
				// Channels configuration
			case UINode\Constants::RABBIT_MQ_CHANNELS_CONFIGURATION_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_CHANNELS_CONFIGURATION_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_CHANNELS_CONFIGURATION_DELETED_ENTITY_ROUTING_KEY:
				// Channels properties
			case UINode\Constants::RABBIT_MQ_CHANNELS_PROPERTY_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_CHANNELS_PROPERTY_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY:
				$result = $this->sender->sendEntity(
					'Exchange:',
					[
						'routing_key' => $routingKey,
						'origin'      => UINode\Constants::NODE_DEVICES_ORIGIN,
						'data'        => $this->dataToArray($message),
					]
				);
				break;

			default:
				throw new Exceptions\InvalidStateException('Unknown routing key');
		}

		if ($result) {
			$this->logger->info('[CONSUMER] Successfully consumed entity message', [
				'routing_key' => $routingKey,
				'origin'      => UINode\Constants::NODE_DEVICES_ORIGIN,
				'data'        => $this->dataToArray($message),
			]);
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getSchemaFile(string $routingKey, string $origin): ?string
	{
		if ($origin === UINode\Constants::NODE_DEVICES_ORIGIN) {
			switch ($routingKey) {
				case UINode\Constants::RABBIT_MQ_DEVICES_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_DEVICES_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_DEVICES_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/devices-module/entity.device.json';

				case UINode\Constants::RABBIT_MQ_DEVICES_CONFIGURATION_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_DEVICES_CONFIGURATION_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_DEVICES_CONFIGURATION_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/devices-module/entity.device.configuration.json';

				case UINode\Constants::RABBIT_MQ_DEVICES_PROPERTY_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_DEVICES_PROPERTY_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_DEVICES_PROPERTY_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/devices-module/entity.device.property.json';

				case UINode\Constants::RABBIT_MQ_CHANNELS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_CHANNELS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_CHANNELS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/devices-module/entity.channel.json';

				case UINode\Constants::RABBIT_MQ_CHANNELS_CONFIGURATION_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_CHANNELS_CONFIGURATION_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_CHANNELS_CONFIGURATION_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/devices-module/entity.channel.configuration.json';

				case UINode\Constants::RABBIT_MQ_CHANNELS_PROPERTY_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_CHANNELS_PROPERTY_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/devices-module/entity.channel.property.json';
			}
		}

		return null;
	}

}
