<?php declare(strict_types = 1);

/**
 * DataSourceHydrator.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           27.05.20
 */

namespace FastyBird\UINode\Hydrators\Widgets\DataSources;

use FastyBird\NodeDatabase\Hydrators as NodeDatabaseHydrators;

/**
 * Data source entity hydrator
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class DataSourceHydrator extends NodeDatabaseHydrators\Hydrator
{

	/** @var string */
	protected $entityIdentifier = self::IDENTIFIER_KEY;

	/** @var string */
	protected $translationDomain = 'node.dataSources';

}
