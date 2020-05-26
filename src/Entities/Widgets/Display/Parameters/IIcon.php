<?php declare(strict_types = 1);

/**
 * IIcon.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Entities\Widgets\Display\Parameters;

use FastyBird\UINode\Types;

/**
 * Display icon parameter interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IIcon
{

	/**
	 * @param Types\WidgetIcons $icon
	 *
	 * @return void
	 */
	public function setIcon(Types\WidgetIcons $icon): void;

	/**
	 * @return Types\WidgetIcons|null
	 */
	public function getIcon(): ?Types\WidgetIcons;

}
