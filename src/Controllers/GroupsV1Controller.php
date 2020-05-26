<?php declare(strict_types = 1);

/**
 * GroupsV1Controller.php
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
use FastyBird\UINode\Entities;
use FastyBird\UINode\Hydrators;
use FastyBird\UINode\Models;
use FastyBird\UINode\Queries;
use FastyBird\UINode\Router;
use FastyBird\UINode\Schemas;
use Fig\Http\Message\StatusCodeInterface;
use IPub\DoctrineCrud\Exceptions as DoctrineCrudExceptions;
use Psr\Http\Message;
use Ramsey\Uuid;
use Throwable;

/**
 * API groups controller
 *
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class GroupsV1Controller extends BaseV1Controller
{

	use Controllers\Finders\TDashboardFinder;

	/** @var Hydrators\Groups\GroupHydrator */
	private $groupsHydrator;

	/** @var Models\Groups\IGroupRepository */
	private $groupRepository;

	/** @var Models\Groups\IGroupsManager */
	private $groupsManager;

	/** @var string */
	protected $translationDomain = 'node.groups';

	/**
	 * @param Models\Groups\IGroupRepository $groupRepository
	 * @param Models\Groups\IGroupsManager $groupsManager
	 * @param Hydrators\Groups\GroupHydrator $groupsHydrator
	 */
	public function __construct(
		Models\Groups\IGroupRepository $groupRepository,
		Models\Groups\IGroupsManager $groupsManager,
		Hydrators\Groups\GroupHydrator $groupsHydrator
	) {
		$this->groupRepository = $groupRepository;
		$this->groupsManager = $groupsManager;
		$this->groupsHydrator = $groupsHydrator;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	public function index(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		// At first, try to load dashboard
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_DASHBOARD_ID));

		$findQuery = new Queries\FindGroupsQuery();
		$findQuery->forDashboard($dashboard);
		$findQuery->sortBy('name');

		$groups = $this->groupRepository->getResultSet($findQuery);

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($groups));
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
		// At first, try to load dashboard
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_DASHBOARD_ID));

		$group = $this->findGroup($request->getAttribute(Router\Router::URL_ITEM_ID), $dashboard);

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($group));
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
		// At first, try to load dashboard
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_DASHBOARD_ID));

		$document = $this->createDocument($request);

		if ($document->getResource()->getType() === Schemas\Groups\GroupSchema::SCHEMA_TYPE) {
			try {
				// Start transaction connection to the database
				$this->getOrmConnection()->beginTransaction();

				$group = $this->groupsManager->create($this->groupsHydrator->hydrate($document));

				// Commit all changes into database
				$this->getOrmConnection()->commit();

			} catch (NodeWebServerExceptions\IJsonApiException $ex) {
				// Revert all changes when error occur
				$this->getOrmConnection()->rollback();

				throw $ex;

			} catch (DoctrineCrudExceptions\MissingRequiredFieldException $ex) {
				// Revert all changes when error occur
				$this->getOrmConnection()->rollback();

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
				$this->getOrmConnection()->rollback();

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
				$this->getOrmConnection()->rollback();

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
				->withEntity(NodeWebServerHttp\ScalarEntity::from($group))
				->withStatus(StatusCodeInterface::STATUS_CREATED);

			return $response;
		}

		throw new NodeWebServerExceptions\JsonApiErrorException(
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

		// At first, try to load dashboard
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_DASHBOARD_ID));

		$group = $this->findGroup($request->getAttribute(Router\Router::URL_ITEM_ID), $dashboard);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			if (
				$document->getResource()->getType() === Schemas\Groups\GroupSchema::SCHEMA_TYPE
				&& $group instanceof Entities\Groups\IGroup
			) {
				$updateGroupData = $this->groupsHydrator->hydrate($document, $group);

			} else {
				throw new NodeWebServerExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('messages.invalidType.heading'),
					$this->translator->translate('messages.invalidType.message'),
					[
						'pointer' => '/data/type',
					]
				);
			}

			$group = $this->groupsManager->update($group, $updateGroupData);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (NodeWebServerExceptions\IJsonApiException $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollback();

			throw $ex;

		} catch (Throwable $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollback();

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
			->withEntity(NodeWebServerHttp\ScalarEntity::from($group));
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
		// At first, try to load dashboard
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_DASHBOARD_ID));

		$group = $this->findGroup($request->getAttribute(Router\Router::URL_ITEM_ID), $dashboard);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			// Move device back into warehouse
			$this->groupsManager->delete($group);

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
			$this->getOrmConnection()->rollback();

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
		// At first, try to load dashboard
		$dashboard = $this->findDashboard($request->getAttribute(Router\Router::URL_DASHBOARD_ID));

		$group = $this->findGroup($request->getAttribute(Router\Router::URL_ITEM_ID), $dashboard);

		$relationEntity = strtolower($request->getAttribute(Router\Router::RELATION_ENTITY));

		if ($relationEntity === Schemas\Groups\GroupSchema::RELATIONSHIPS_DASHBOARD) {
			return $response
				->withEntity(NodeWebServerHttp\ScalarEntity::from($group->getDashboard()));

		} elseif ($relationEntity === Schemas\Groups\GroupSchema::RELATIONSHIPS_WIDGETS) {
			return $response
				->withEntity(NodeWebServerHttp\ScalarEntity::from($group->getWidgets()));
		}

		$this->throwUnknownRelation($relationEntity);

		return $response;
	}

	/**
	 * @param string $id
	 * @param Entities\Dashboards\IDashboard $dashboard
	 *
	 * @return Entities\Groups\IGroup
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	private function findGroup(
		string $id,
		Entities\Dashboards\IDashboard $dashboard
	): Entities\Groups\IGroup {
		try {
			$findQuery = new Queries\FindGroupsQuery();
			$findQuery->byId(Uuid\Uuid::fromString($id));
			$findQuery->forDashboard($dashboard);

			$group = $this->groupRepository->findOneBy($findQuery);

			if ($group === null) {
				throw new NodeWebServerExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_NOT_FOUND,
					$this->translator->translate('//node.base.messages.groupNotFound.heading'),
					$this->translator->translate('//node.base.messages.groupNotFound.message')
				);
			}

		} catch (Uuid\Exception\InvalidUuidStringException $ex) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//node.base.messages.groupNotFound.heading'),
				$this->translator->translate('//node.base.messages.groupNotFound.message')
			);
		}

		return $group;
	}

}
