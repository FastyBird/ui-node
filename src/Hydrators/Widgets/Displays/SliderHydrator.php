<?php declare(strict_types = 1);

/**
 * SliderHydrator.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Hydrators\Widgets\Displays;

use FastyBird\UINode\Entities;

/**
 * Analog value widget display entity hydrator
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class SliderHydrator extends DisplayHydrator
{

	/** @var string[] */
	protected $attributes = [
		'minimum_value' => 'minimumValue',
		'maximum_value' => 'maximumValue',
		'step_value'    => 'stepValue',
		'precision'     => 'precision',
	];

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Widgets\Display\Slider::class;
	}

}
