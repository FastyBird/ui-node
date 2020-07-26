<?php declare(strict_types = 1);

/**
 * ISlider.php
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

namespace FastyBird\UINode\Entities\Widgets\Display;

use FastyBird\UINode\Entities;

/**
 * Slider display interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ISlider extends IDisplay,
	Entities\Widgets\Display\Parameters\IMinimumValue,
	Entities\Widgets\Display\Parameters\IMaximumValue,
	Entities\Widgets\Display\Parameters\IStepValue,
	Entities\Widgets\Display\Parameters\IPrecision
{

	public const DISPLAY_TYPE = 'slider';

}
