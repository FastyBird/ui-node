<?php declare(strict_types = 1);

/**
 * IGroupRepository.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Models\Groups;

use FastyBird\UINode\Entities;
use FastyBird\UINode\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Dashboard group repository interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IGroupRepository
{

	/**
	 * @param Queries\FindGroupsQuery $queryObject
	 *
	 * @return Entities\Groups\IGroup|null
	 *
	 * @phpstan-template T of Entities\Groups\Group
	 * @phpstan-param    Queries\FindGroupsQuery<T> $queryObject
	 */
	public function findOneBy(Queries\FindGroupsQuery $queryObject): ?Entities\Groups\IGroup;

	/**
	 * @param Queries\FindGroupsQuery $queryObject
	 *
	 * @return Entities\Groups\IGroup[]
	 *
	 * @phpstan-template T of Entities\Groups\Group
	 * @phpstan-param    Queries\FindGroupsQuery<T> $queryObject
	 */
	public function findAllBy(Queries\FindGroupsQuery $queryObject): array;

	/**
	 * @param Queries\FindGroupsQuery $queryObject
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-template T of Entities\Groups\Group
	 * @phpstan-param    Queries\FindGroupsQuery<T> $queryObject
	 * @phpstan-return   DoctrineOrmQuery\ResultSet<T>
	 */
	public function getResultSet(Queries\FindGroupsQuery $queryObject): DoctrineOrmQuery\ResultSet;

}
