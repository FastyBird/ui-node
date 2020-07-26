<?php declare(strict_types = 1);

/**
 * IGroupedButton.php
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
 * Grouped button display interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IGroupedButton extends IDisplay,
	Entities\Widgets\Display\Parameters\IIcon
{

	public const DISPLAY_TYPE = 'groupedButton';

}
