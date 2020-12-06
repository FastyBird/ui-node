<?php declare(strict_types = 1);

/**
 * ServerStartHandler.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Events
 * @since          0.1.0
 *
 * @date           26.07.20
 */

namespace FastyBird\UINode\Events;

use IPub\WebSockets;
use Nette;
use Psr\Log;
use React\EventLoop;
use React\Socket;
use Throwable;

/**
 * Http server start handler
 *
 * @package         FastyBird:UINode!
 * @subpackage      Events
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ServerStartHandler
{

	use Nette\SmartObject;

	/** @var WebSockets\Server\Handlers */
	private $handlers;

	/** @var EventLoop\LoopInterface */
	private $loop;

	/** @var WebSockets\Server\Configuration */
	private $configuration;

	/** @var Log\LoggerInterface */
	private $logger;

	public function __construct(
		WebSockets\Server\Handlers $handlers,
		EventLoop\LoopInterface $loop,
		WebSockets\Server\Configuration $configuration,
		?Log\LoggerInterface $logger = null
	) {
		$this->loop = $loop;

		$this->configuration = $configuration;
		$this->handlers = $handlers;

		$this->logger = $logger ?? new Log\NullLogger();
	}

	/**
	 * @return void
	 *
	 * @throws Throwable
	 */
	public function __invoke(): void
	{
		$client = $this->configuration->getAddress() . ':' . $this->configuration->getPort();
		$socket = new Socket\Server($client, $this->loop);

		$socket->on('connection', function (Socket\ConnectionInterface $connection): void {
			$this->handlers->handleConnect($connection);
		});

		$socket->on('error', function (Throwable $ex): void {
			$this->logger->error('Could not establish connection: ' . $ex->getMessage());
		});

		$this->logger->debug(sprintf('Launching WebSockets WS Server on: %s:%s', $this->configuration->getAddress(), $this->configuration->getPort()));
	}

}
