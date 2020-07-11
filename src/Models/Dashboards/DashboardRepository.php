<?php declare(strict_types = 1);

/**
 * DashboardRepository.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Models\Dashboards;

use Doctrine\Common;
use Doctrine\Persistence;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Exceptions;
use FastyBird\UINode\Queries;
use IPub\DoctrineOrmQuery;
use Nette;
use Throwable;

/**
 * Dashboard repository
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DashboardRepository implements IDashboardRepository
{

	use Nette\SmartObject;

	/** @var Common\Persistence\ManagerRegistry */
	private $managerRegistry;

	/** @var Persistence\ObjectRepository<Entities\Dashboards\Dashboard>|null */
	public $repository = null;

	public function __construct(Common\Persistence\ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findOneBy(Queries\FindDashboardsQuery $queryObject): ?Entities\Dashboards\IDashboard
	{
		/** @var Entities\Dashboards\IDashboard|null $dashboard */
		$dashboard = $queryObject->fetchOne($this->getRepository());

		return $dashboard;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws Throwable
	 */
	public function findAllBy(Queries\FindDashboardsQuery $queryObject): array
	{
		$result = $queryObject->fetch($this->getRepository());

		return is_array($result) ? $result : $result->toArray();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws Throwable
	 */
	public function getResultSet(
		Queries\FindDashboardsQuery $queryObject
	): DoctrineOrmQuery\ResultSet {
		$result = $queryObject->fetch($this->getRepository());

		if (!$result instanceof DoctrineOrmQuery\ResultSet) {
			throw new Exceptions\InvalidStateException('Result set for given query could not be loaded.');
		}

		return $result;
	}

	/**
	 * @return Persistence\ObjectRepository<Entities\Dashboards\Dashboard>
	 */
	private function getRepository(): Persistence\ObjectRepository
	{
		if ($this->repository === null) {
			$this->repository = $this->managerRegistry->getRepository(Entities\Dashboards\Dashboard::class);
		}

		return $this->repository;
	}

}
