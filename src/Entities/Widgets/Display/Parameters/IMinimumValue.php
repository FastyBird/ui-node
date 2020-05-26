<?php declare(strict_types = 1);

/**
 * IMinimumValue.php
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

/**
 * Display minimum value parameter interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IMinimumValue
{

	/**
	 * @param float $minimumValue
	 *
	 * @return void
	 */
	public function setMinimumValue(float $minimumValue): void;

	/**
	 * @return float|null
	 */
	public function getMinimumValue(): ?float;

}
