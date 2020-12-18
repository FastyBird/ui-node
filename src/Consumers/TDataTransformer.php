<?php declare(strict_types = 1);

/**
 * TDataTransformer.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 * @since          0.1.0
 *
 * @date           06.05.20
 */

namespace FastyBird\UINode\Consumers;

use Nette\Utils;

/**
 * Sockets data transformer trait
 *
 * @package        FastyBird:UINode!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
trait TDataTransformer
{

	/**
	 * @param Utils\ArrayHash $data
	 *
	 * @return mixed[]
	 */
	protected function dataToArray(Utils\ArrayHash $data): array
	{
		$transformed = (array) $data;

		foreach ($transformed as $key => $value) {
			if ($value instanceof Utils\ArrayHash) {
				$transformed[$key] = $this->dataToArray($value);
			}
		}

		return $transformed;
	}

}
