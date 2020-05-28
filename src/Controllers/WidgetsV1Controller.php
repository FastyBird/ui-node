<?php declare(strict_types = 1);

/**
 * WidgetsV1Controller.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Controllers;

use Doctrine;
use FastyBird\NodeWebServer\Exceptions as NodeWebServerExceptions;
use FastyBird\NodeWebServer\Http as NodeWebServerHttp;
use FastyBird\UINode\Controllers;
use FastyBird\UINode\Hydrators;
use FastyBird\UINode\Models;
use FastyBird\UINode\Queries;
use FastyBird\UINode\Router;
use FastyBird\UINode\Schemas;
use Fig\Http\Message\StatusCodeInterface;
use IPub\DoctrineCrud\Exceptions as DoctrineCrudExceptions;
use Psr\Http\Message;
use Throwable;

/**
 * API widgets controller
 *
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class WidgetsV1Controller extends BaseV1Controller
{

	use Controllers\Finders\TWidgetFinder;

	/** @var Hydrators\Widgets\AnalogActuatorWidgetHydrator */
	private $analogActuatorHydrator;

	/** @var Hydrators\Widgets\AnalogSensorWidgetHydrator */
	private $analogSensorHydrator;

	/** @var Hydrators\Widgets\DigitalActuatorWidgetHydrator */
	private $digitalActuatorHydrator;

	/** @var Hydrators\Widgets\DigitalSensorWidgetHydrator */
	private $digitalSensorHydrator;

	/** @var Models\Widgets\IWidgetRepository */
	private $widgetRepository;

	/** @var Models\Widgets\IWidgetsManager */
	private $widgetsManager;

	/** @var string */
	protected $translationDomain = 'node.widgets';

	/**
	 * @param Models\Widgets\IWidgetRepository $widgetRepository
	 * @param Models\Widgets\IWidgetsManager $widgetsManager
	 * @param Hydrators\Widgets\AnalogActuatorWidgetHydrator $analogActuatorHydrator
	 * @param Hydrators\Widgets\AnalogSensorWidgetHydrator $analogSensorHydrator
	 * @param Hydrators\Widgets\DigitalActuatorWidgetHydrator $digitalActuatorHydrator
	 * @param Hydrators\Widgets\DigitalSensorWidgetHydrator $digitalSensorHydrator
	 */
	public function __construct(
		Models\Widgets\IWidgetRepository $widgetRepository,
		Models\Widgets\IWidgetsManager $widgetsManager,
		Hydrators\Widgets\AnalogActuatorWidgetHydrator $analogActuatorHydrator,
		Hydrators\Widgets\AnalogSensorWidgetHydrator $analogSensorHydrator,
		Hydrators\Widgets\DigitalActuatorWidgetHydrator $digitalActuatorHydrator,
		Hydrators\Widgets\DigitalSensorWidgetHydrator $digitalSensorHydrator
	) {
		$this->widgetRepository = $widgetRepository;
		$this->widgetsManager = $widgetsManager;
		$this->analogActuatorHydrator = $analogActuatorHydrator;
		$this->analogSensorHydrator = $analogSensorHydrator;
		$this->digitalActuatorHydrator = $digitalActuatorHydrator;
		$this->digitalSensorHydrator = $digitalSensorHydrator;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 */
	public function index(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$findQuery = new Queries\FindWidgetsQuery();
		$findQuery->sortBy('name');

		$widgets = $this->widgetRepository->getResultSet($findQuery);

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($widgets));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	public function read(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_ITEM_ID));

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($widget));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function create(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$document = $this->createDocument($request);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			switch ($document->getResource()->getType()) {
				case Schemas\Widgets\AnalogActuatorSchema::SCHEMA_TYPE:
					$widget = $this->widgetsManager->create($this->analogActuatorHydrator->hydrate($document));
					break;

				case Schemas\Widgets\DigitalActuatorSchema::SCHEMA_TYPE:
					$widget = $this->widgetsManager->create($this->digitalActuatorHydrator->hydrate($document));
					break;

				case Schemas\Widgets\AnalogSensorSchema::SCHEMA_TYPE:
					$widget = $this->widgetsManager->create($this->analogSensorHydrator->hydrate($document));
					break;

				case Schemas\Widgets\DigitalSensorSchema::SCHEMA_TYPE:
					$widget = $this->widgetsManager->create($this->digitalSensorHydrator->hydrate($document));
					break;

				default:
					throw new NodeWebServerExceptions\JsonApiErrorException(
						StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
						$this->translator->translate('messages.invalidType.heading'),
						$this->translator->translate('messages.invalidType.message'),
						[
							'pointer' => '/data/type',
						]
					);
			}

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (NodeWebServerExceptions\IJsonApiException $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			throw $ex;

		} catch (DoctrineCrudExceptions\MissingRequiredFieldException $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			$pointer = 'data/attributes/' . $ex->getField();

			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => $pointer,
				]
			);

		} catch (DoctrineCrudExceptions\EntityCreationException $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => 'data/attributes/' . $ex->getField(),
				]
			);

		} catch (Throwable $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.notCreated.heading'),
				$this->translator->translate('messages.notCreated.message')
			);
		}

		/** @var NodeWebServerHttp\Response $response */
		$response = $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($widget))
			->withStatus(StatusCodeInterface::STATUS_CREATED);

		return $response;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function update(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$document = $this->createDocument($request);

		if ($request->getAttribute(Router\Router::URL_ITEM_ID) !== $document->getResource()->getIdentifier()->getId()) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_BAD_REQUEST,
				$this->translator->translate('//node.base.messages.invalid.heading'),
				$this->translator->translate('//node.base.messages.invalid.message')
			);
		}

		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_ITEM_ID));

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			switch ($document->getResource()->getType()) {
				case Schemas\Widgets\AnalogActuatorSchema::SCHEMA_TYPE:
					$updateWidgetData = $this->analogActuatorHydrator->hydrate($document, $widget);
					break;

				case Schemas\Widgets\DigitalActuatorSchema::SCHEMA_TYPE:
					$updateWidgetData = $this->digitalActuatorHydrator->hydrate($document, $widget);
					break;

				case Schemas\Widgets\AnalogSensorSchema::SCHEMA_TYPE:
					$updateWidgetData = $this->analogSensorHydrator->hydrate($document, $widget);
					break;

				case Schemas\Widgets\DigitalSensorSchema::SCHEMA_TYPE:
					$updateWidgetData = $this->digitalSensorHydrator->hydrate($document, $widget);
					break;

				default:
					throw new NodeWebServerExceptions\JsonApiErrorException(
						StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
						$this->translator->translate('messages.invalidType.heading'),
						$this->translator->translate('messages.invalidType.message'),
						[
							'pointer' => '/data/type',
						]
					);
			}

			$widget = $this->widgetsManager->update($widget, $updateWidgetData);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (NodeWebServerExceptions\IJsonApiException $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			throw $ex;

		} catch (Throwable $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.notUpdated.heading'),
				$this->translator->translate('messages.notUpdated.message')
			);
		}

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($widget));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function delete(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_ITEM_ID));

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			// Move device back into warehouse
			$this->widgetsManager->delete($widget);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (Throwable $ex) {
			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.notDeleted.heading'),
				$this->translator->translate('messages.notDeleted.message')
			);
		}

		/** @var NodeWebServerHttp\Response $response */
		$response = $response->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);

		return $response;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	public function readRelationship(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_ITEM_ID));

		$relationEntity = strtolower($request->getAttribute(Router\Router::RELATION_ENTITY));

		if ($relationEntity === Schemas\Widgets\WidgetSchema::RELATIONSHIPS_GROUPS) {
			return $response
				->withEntity(NodeWebServerHttp\ScalarEntity::from($widget->getGroups()));
		}

		$this->throwUnknownRelation($relationEntity);

		return $response;
	}

}
