<?php declare(strict_types = 1);

/**
 * IEntityParams.php
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

namespace FastyBird\UINode\Entities;

use Nette\Utils;

/**
 * Entity params field interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IEntityParams
{

	/**
	 * @param mixed[] $params
	 *
	 * @return void
	 */
	public function setParams(array $params): void;

	/**
	 * @return Utils\ArrayHash
	 */
	public function getParams(): Utils\ArrayHash;

	/**
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function setParam(string $key, $value = ''): void;

	/**
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed|null
	 */
	public function getParam(string $key, $default = null);

}
