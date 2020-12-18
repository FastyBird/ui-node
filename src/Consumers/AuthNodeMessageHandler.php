<?php declare(strict_types = 1);

/**
 * AuthNodeMessageHandler.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
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
 * Auth node message consumer
 *
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class AuthNodeMessageHandler extends MessageHandler
{

	use TDataTransformer;

	/** @var UIModuleSockets\ISender */
	private UIModuleSockets\ISender $sender;

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
			// Accounts
			case UINode\Constants::RABBIT_MQ_ACCOUNTS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_ACCOUNTS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_ACCOUNTS_DELETED_ENTITY_ROUTING_KEY:
				// Emails
			case UINode\Constants::RABBIT_MQ_EMAILS_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_EMAILS_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_EMAILS_DELETED_ENTITY_ROUTING_KEY:
				// Identities
			case UINode\Constants::RABBIT_MQ_IDENTITIES_CREATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_IDENTITIES_UPDATED_ENTITY_ROUTING_KEY:
			case UINode\Constants::RABBIT_MQ_IDENTITIES_DELETED_ENTITY_ROUTING_KEY:
				$result = $this->sender->sendEntity(
					'Exchange:',
					[
						'routing_key' => $routingKey,
						'origin'      => UINode\Constants::NODE_AUTH_ORIGIN,
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
				'origin'      => UINode\Constants::NODE_AUTH_ORIGIN,
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
		if ($origin === UINode\Constants::NODE_AUTH_ORIGIN) {
			switch ($routingKey) {
				case UINode\Constants::RABBIT_MQ_ACCOUNTS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_ACCOUNTS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_ACCOUNTS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/auth-module/entity.account.json';

				case UINode\Constants::RABBIT_MQ_EMAILS_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_EMAILS_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_EMAILS_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/auth-module/entity.email.json';

				case UINode\Constants::RABBIT_MQ_IDENTITIES_CREATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_IDENTITIES_UPDATED_ENTITY_ROUTING_KEY:
				case UINode\Constants::RABBIT_MQ_IDENTITIES_DELETED_ENTITY_ROUTING_KEY:
					return ModulesMetadata\Constants::RESOURCES_FOLDER . '/schemas/auth-module/entity.identity.json';
			}
		}

		return null;
	}

}
