<?php declare(strict_types = 1);

/**
 * IDataSource.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Entities\Widgets\DataSources;

use FastyBird\NodeDatabase\Entities as NodeDatabaseEntities;
use FastyBird\UINode\Entities;
use IPub\DoctrineTimestampable;

/**
 * Widget data source entity interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDataSource extends NodeDatabaseEntities\IEntity,
	NodeDatabaseEntities\IEntityParams,
	DoctrineTimestampable\Entities\IEntityCreated, DoctrineTimestampable\Entities\IEntityUpdated
{

	/**
	 * @return Entities\Widgets\IWidget
	 */
	public function getWidget(): Entities\Widgets\IWidget;

}
