<?php declare(strict_types = 1);

/**
 * GroupRepository.php
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

namespace FastyBird\UINode\Models\Groups;

use Doctrine\Common;
use Doctrine\ORM;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Exceptions;
use FastyBird\UINode\Queries;
use IPub\DoctrineOrmQuery;
use Nette;
use Throwable;

/**
 * Dashboard group repository
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class GroupRepository implements IGroupRepository
{

	use Nette\SmartObject;

	/** @var Common\Persistence\ManagerRegistry */
	private $managerRegistry;

	/** @var ORM\EntityRepository<Entities\Groups\Group>|null */
	public $repository = null;

	public function __construct(Common\Persistence\ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findOneBy(Queries\FindGroupsQuery $queryObject): ?Entities\Groups\IGroup
	{
		/** @var Entities\Groups\IGroup|null $group */
		$group = $queryObject->fetchOne($this->getRepository());

		return $group;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws Throwable
	 */
	public function findAllBy(Queries\FindGroupsQuery $queryObject): array
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
		Queries\FindGroupsQuery $queryObject
	): DoctrineOrmQuery\ResultSet {
		$result = $queryObject->fetch($this->getRepository());

		if (!$result instanceof DoctrineOrmQuery\ResultSet) {
			throw new Exceptions\InvalidStateException('Result set for given query could not be loaded.');
		}

		return $result;
	}

	/**
	 * @return ORM\EntityRepository<Entities\Groups\Group>
	 */
	private function getRepository(): ORM\EntityRepository
	{
		if ($this->repository === null) {
			$this->repository = $this->managerRegistry->getRepository(Entities\Groups\Group::class);
		}

		return $this->repository;
	}

}
