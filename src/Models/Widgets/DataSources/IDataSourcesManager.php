<?php declare(strict_types = 1);

/**
 * IDataSourcesManager.php
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
use Nette\Utils;

/**
 * Widgets data sources entities manager interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDataSourcesManager
{

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Widgets\DataSources\IDataSource
	 */
	public function create(
		Utils\ArrayHash $values
	): Entities\Widgets\DataSources\IDataSource;

	/**
	 * @param Entities\Widgets\DataSources\IDataSource $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Widgets\DataSources\IDataSource
	 */
	public function update(
		Entities\Widgets\DataSources\IDataSource $entity,
		Utils\ArrayHash $values
	): Entities\Widgets\DataSources\IDataSource;

	/**
	 * @param Entities\Widgets\DataSources\IDataSource $entity
	 *
	 * @return bool
	 */
	public function delete(
		Entities\Widgets\DataSources\IDataSource $entity
	): bool;

}
