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

}
