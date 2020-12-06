<?php declare(strict_types = 1);

/**
 * TriggersNodeMessageHandler.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 * @since          0.1.0
 *
 * @date           31.08.20
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
 * Triggers node message consumer
 *
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class TriggersNodeMessageHandler extends MessageHandler
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
			// Triggers
			case UINode\Constants::RABBIT_MQ_TRIGGERS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_DELETED_ENTITY_ROUTING_KEY:
				// Actions
			case UINode\Constants::RABBIT_MQ_TRIGGERS_ACTIONS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_ACTIONS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_ACTIONS_DELETED_ENTITY_ROUTING_KEY:
				// Notifications
			case UINode\Constants::RABBIT_MQ_TRIGGERS_NOTIFICATIONS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_NOTIFICATIONS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_NOTIFICATIONS_DELETED_ENTITY_ROUTING_KEY:
				// Conditions
			case UINode\Constants::RABBIT_MQ_TRIGGERS_CONDITIONS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_CONDITIONS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_TRIGGERS_CONDITIONS_DELETED_ENTITY_ROUTING_KEY:
				$result = $this->sender->sendEntity(
					'Exchange:',
					[
						'routing_key' => $routingKey,
						'origin'      => UINode\Constants::NODE_TRIGGERS_ORIGIN,
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
				'origin'      => UINode\Constants::NODE_TRIGGERS_ORIGIN,
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
		if ($origin === UINode\Constants::NODE_TRIGGERS_ORIGIN) {
			switch ($routingKey) {
				case UINode\Constants::RABBIT_MQ_TRIGGERS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/triggers-module/entity.trigger.json';

				case UINode\Constants::RABBIT_MQ_TRIGGERS_ACTIONS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_ACTIONS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_ACTIONS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/triggers-module/entity.action.json';

				case UINode\Constants::RABBIT_MQ_TRIGGERS_NOTIFICATIONS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_NOTIFICATIONS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_NOTIFICATIONS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/triggers-module/entity.notification.json';

				case UINode\Constants::RABBIT_MQ_TRIGGERS_CONDITIONS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_CONDITIONS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_TRIGGERS_CONDITIONS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/triggers-module/entity.condition.json';
			}
		}

		return null;
	}

}
