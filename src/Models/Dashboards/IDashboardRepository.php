<?php declare(strict_types = 1);

/**
 * IDashboardRepository.php
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

use FastyBird\UINode\Entities;
use FastyBird\UINode\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Dashboard repository interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDashboardRepository
{

	/**
	 * @param Queries\FindDashboardsQuery $queryObject
	 *
	 * @return Entities\Dashboards\IDashboard|null
	 *
	 * @phpstan-template T of Entities\Dashboards\Dashboard
	 * @phpstan-param    Queries\FindDashboardsQuery<T> $queryObject
	 */
	public function findOneBy(Queries\FindDashboardsQuery $queryObject): ?Entities\Dashboards\IDashboard;

	/**
	 * @param Queries\FindDashboardsQuery $queryObject
	 *
	 * @return Entities\Dashboards\IDashboard[]
	 *
	 * @phpstan-template T of Entities\Dashboards\Dashboard
	 * @phpstan-param    Queries\FindDashboardsQuery<T> $queryObject
	 */
	public function findAllBy(Queries\FindDashboardsQuery $queryObject): array;

	/**
	 * @param Queries\FindDashboardsQuery $queryObject
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-template T of Entities\Dashboards\Dashboard
	 * @phpstan-param    Queries\FindDashboardsQuery<T> $queryObject
	 * @phpstan-return   DoctrineOrmQuery\ResultSet<T>
	 */
	public function getResultSet(Queries\FindDashboardsQuery $queryObject): DoctrineOrmQuery\ResultSet;

}
