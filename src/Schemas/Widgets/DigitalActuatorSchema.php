<?php declare(strict_types = 1);

/**
 * DigitalActuatorSchema.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           13.04.19
 */

namespace FastyBird\UINode\Schemas\Widgets;

use FastyBird\UINode\Entities;

/**
 * Digital actuator widget entity schema
 *
 * @package        FastyBird:IOTApiModule!
 * @subpackage     Schemas
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends WidgetSchema<Entities\Widgets\IDigitalActuator>
 */
final class DigitalActuatorSchema extends WidgetSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/widget-digital-actuator';

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
		return Entities\Widgets\DigitalActuator::class;
	}

}
