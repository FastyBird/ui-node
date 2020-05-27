<?php declare(strict_types = 1);

/**
 * IDataSourceRepository.php
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

namespace FastyBird\UINode\Models\Widgets\DataSources;

use FastyBird\UINode\Entities;
use FastyBird\UINode\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Widget data source repository interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDataSourceRepository
{

	/**
	 * @param Queries\FindDataSourcesQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Widgets\DataSources\IDataSource|null
	 *
	 * @phpstan-template T of Entities\Widgets\DataSources\DataSource
	 * @phpstan-param    Queries\FindDataSourcesQuery<T> $queryObject
	 * @phpstan-param    class-string<T> $type
	 */
	public function findOneBy(
		Queries\FindDataSourcesQuery $queryObject,
		string $type = Entities\Widgets\DataSources\DataSource::class
	): ?Entities\Widgets\DataSources\IDataSource;

	/**
	 * @param Queries\FindDataSourcesQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Widgets\DataSources\IDataSource[]
	 *
	 * @phpstan-template T of Entities\Widgets\DataSources\DataSource
	 * @phpstan-param    Queries\FindDataSourcesQuery<T> $queryObject
	 * @phpstan-param    class-string<T> $type
	 */
	public function findAllBy(
		Queries\FindDataSourcesQuery $queryObject,
		string $type = Entities\Widgets\DataSources\DataSource::class
	): array;

	/**
	 * @param Queries\FindGroupsQuery $queryObject
	 * @param string $type
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-template T of Entities\Widgets\DataSources\DataSource
	 * @phpstan-param    Queries\FindDataSourcesQuery<T> $queryObject
	 * @phpstan-param    class-string<T> $type
	 * @phpstan-return   DoctrineOrmQuery\ResultSet<T>
	 */
	public function getResultSet(
		Queries\FindDataSourcesQuery $queryObject,
		string $type = Entities\Widgets\DataSources\DataSource::class
	): DoctrineOrmQuery\ResultSet;

}
