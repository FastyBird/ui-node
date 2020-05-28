<?php declare(strict_types = 1);

/**
 * IDashboard.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Entities\Dashboards;

use FastyBird\NodeDatabase\Entities as NodeDatabaseEntities;
use FastyBird\UINode\Entities;
use IPub\DoctrineTimestampable;

/**
 * Dashboard entity interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDashboard extends NodeDatabaseEntities\IEntity,
	NodeDatabaseEntities\IEntityParams,
	DoctrineTimestampable\Entities\IEntityCreated, DoctrineTimestampable\Entities\IEntityUpdated
{

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName(string $name): void;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string|null $comment
	 *
	 * @return void
	 */
	public function setComment(?string $comment = null): void;

	/**
	 * @return string|null
	 */
	public function getComment(): ?string;

	/**
	 * @param int $priority
	 *
	 * @return void
	 */
	public function setPriority(int $priority): void;

	/**
	 * @return int
	 */
	public function getPriority(): int;

	/**
	 * @param Entities\Groups\IGroup[] $groups
	 *
	 * @return void
	 */
	public function setGroups(array $groups): void;

	/**
	 * @param Entities\Groups\IGroup $group
	 *
	 * @return void
	 */
	public function addGroup(Entities\Groups\IGroup $group): void;

	/**
	 * @return Entities\Groups\IGroup[]
	 */
	public function getGroups(): array;

	/**
	 * @param string $id
	 *
	 * @return Entities\Groups\IGroup|null
	 */
	public function getGroup(string $id): ?Entities\Groups\IGroup;

	/**
	 * @param Entities\Groups\IGroup $group
	 *
	 * @return void
	 */
	public function removeGroup(Entities\Groups\IGroup $group): void;

}
