<?php declare(strict_types = 1);

/**
 * DigitalValueSchema.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Schemas\Widgets\Display;

use FastyBird\UINode\Entities;

/**
 * Digital value widget display entity schema
 *
 * @package            FastyBird:UINode!
 * @subpackage         Schemas
 *
 * @author             Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends    DisplaySchema<Entities\Widgets\Display\IDigitalValue>
 */
final class DigitalValueSchema extends DisplaySchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/widget-display-digital-value';

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityClass(): string
	{
		return Entities\Widgets\Display\DigitalValue::class;
	}

}
