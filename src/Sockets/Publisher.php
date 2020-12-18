<?php declare(strict_types = 1);

/**
 * Publisher.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Sockets
 * @since          0.1.0
 *
 * @date           06.12.20
 */

namespace FastyBird\UINode\Sockets;

use FastyBird\RabbitMqPlugin\Publishers as RabbitMqPluginPublishers;
use FastyBird\UIModule\Sockets as UIModuleSockets;

/**
 * Rabbit MQ publisher
 *
 * @package        FastyBird:UINode!
 * @subpackage     Sockets
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class Publisher implements UIModuleSockets\IPublisher
{

	/** @var RabbitMqPluginPublishers\IRabbitMqPublisher */
	private RabbitMqPluginPublishers\IRabbitMqPublisher $rabbitMqPublisher;

	public function __construct(
		RabbitMqPluginPublishers\IRabbitMqPublisher $rabbitMqPublisher
	) {
		$this->rabbitMqPublisher = $rabbitMqPublisher;
	}

	/**
	 * {@inheritDoc}
	 */
	public function publish(
		string $key,
		array $data
	): void {
		$this->rabbitMqPublisher->publish(
			$key,
			$data
		);
	}

}
