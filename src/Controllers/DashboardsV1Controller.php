<?php declare(strict_types = 1);

/**
 * DevicesController.php
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
use FastyBird\NodeJsonApi\Exceptions as NodeJsonApiExceptions;
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
 * API dashboards controller
 *
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DashboardsV1Controller extends BaseV1Controller
{

	use Controllers\Finders\TDashboardFinder;

	/** @var Hydrators\Dashboards\DashboardHydrator */
	private $dashboardHydrator;

	/** @var Models\Dashboards\IDashboardRepository */
	private $dashboardRepository;

	/** @var Models\Dashboards\IDashboardsManager */
	private $dashboardsManager;

	/** @var Models\Groups\IGroupsManager */
	private $groupsManager;

	/** @var string */
	protected $translationDomain = 'node.dashboards';

	/**
	 * @param Models\Dashboards\IDashboardRepository $dashboardRepository
	 * @param Models\Dashboards\IDashboardsManager $dashboardsManager
	 * @param Models\Groups\IGroupsManager $groupsManager
	 * @param Hydrators\Dashboards\DashboardHydrator $dashboardHydrator
	 */
	public function __construct(
		Models\Dashboards\IDashboardRepository $dashboardRepository,
		Models\Dashboards\IDashboardsManager $dashboardsManager,
		Models\Groups\IGroupsManager $groupsManager,
		Hydrators\Dashboards\DashboardHydrator $dashboardHydrator
	) {
		$this->dashboardRepository = $dashboardRepository;
		$this->dashboardsManager = $dashboardsManager;
		$this->groupsManager = $groupsManager;
		$this->dashboardHydrator = $dashboardHydrator;
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
		$findQuery = new Queries\FindDashboardsQuery();

		$dashboards = $this->dashboardRepository->getResultSet($findQuery);

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($dashboards));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	public function read(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_ITEM_ID));

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($dashboard));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function create(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$document = $this->createDocument($request);

		if ($document->getResource()->getType() === Schemas\Dashboards\DashboardSchema::SCHEMA_TYPE) {
			try {
				// Start transaction connection to the database
				$this->getOrmConnection()->beginTransaction();

				$dashboard = $this->dashboardsManager->create($this->dashboardHydrator->hydrate($document));

				// Commit all changes into database
				$this->getOrmConnection()->commit();

			} catch (NodeJsonApiExceptions\IJsonApiException $ex) {
				// Revert all changes when error occur
				$this->getOrmConnection()->rollBack();

				throw $ex;

			} catch (DoctrineCrudExceptions\MissingRequiredFieldException $ex) {
				// Revert all changes when error occur
				$this->getOrmConnection()->rollBack();

				$pointer = 'data/attributes/' . $ex->getField();

				throw new NodeJsonApiExceptions\JsonApiErrorException(
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

				throw new NodeJsonApiExceptions\JsonApiErrorException(
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

				throw new NodeJsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('messages.notCreated.heading'),
					$this->translator->translate('messages.notCreated.message')
				);
			}

			/** @var NodeWebServerHttp\Response $response */
			$response = $response
				->withEntity(NodeWebServerHttp\ScalarEntity::from($dashboard))
				->withStatus(StatusCodeInterface::STATUS_CREATED);

			return $response;
		}

		throw new NodeJsonApiExceptions\JsonApiErrorException(
			StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
			$this->translator->translate('messages.invalidType.heading'),
			$this->translator->translate('messages.invalidType.message'),
			[
				'pointer' => '/data/type',
			]
		);
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function update(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$document = $this->createDocument($request);

		if ($request->getAttribute(Router\Router::URL_ITEM_ID) !== $document->getResource()->getIdentifier()->getId()) {
			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_BAD_REQUEST,
				$this->translator->translate('//node.base.messages.invalid.heading'),
				$this->translator->translate('//node.base.messages.invalid.message')
			);
		}

		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_ITEM_ID));

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			if ($document->getResource()->getType() === Schemas\Dashboards\DashboardSchema::SCHEMA_TYPE) {
				$updateDashboardData = $this->dashboardHydrator->hydrate($document, $dashboard);

			} else {
				throw new NodeJsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('messages.invalidType.heading'),
					$this->translator->translate('messages.invalidType.message'),
					[
						'pointer' => '/data/type',
					]
				);
			}

			$dashboard = $this->dashboardsManager->update($dashboard, $updateDashboardData);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (NodeJsonApiExceptions\IJsonApiException $ex) {
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

			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.notUpdated.heading'),
				$this->translator->translate('messages.notUpdated.message')
			);
		}

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($dashboard));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function delete(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_ITEM_ID));

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			foreach ($dashboard->getGroups() as $group) {
				// Remove channels. Newly connected device will be reinitialized with all channels
				$this->groupsManager->delete($group);
			}

			// Move device back into warehouse
			$this->dashboardsManager->delete($dashboard);

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

			throw new NodeJsonApiExceptions\JsonApiErrorException(
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
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	public function readRelationship(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_ITEM_ID));

		$relationEntity = strtolower($request->getAttribute(Router\Router::RELATION_ENTITY));

		if ($relationEntity === Schemas\Dashboards\DashboardSchema::RELATIONSHIPS_GROUPS) {
			return $response
				->withEntity(NodeWebServerHttp\ScalarEntity::from($dashboard->getGroups()));
		}

		$this->throwUnknownRelation($relationEntity);

		return $response;
	}

}
