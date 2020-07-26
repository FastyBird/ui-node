<?php declare(strict_types = 1);

/**
 * TPrecision.php
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

namespace FastyBird\UINode\Entities\Widgets\Display\Parameters;

use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * Display precision parameter
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @method void setParam(string $key, $value = null)
 * @method mixed getParam(string $key, $default = null)
 */
trait TPrecision
{

	/**
	 * @var int|null
	 *
	 * @IPubDoctrine\Crud(is={"writable"})
	 */
	protected $precision;

	/**
	 * @param int|null $precision
	 *
	 * @return void
	 */
	public function setPrecision(?int $precision): void
	{
		$this->precision = $precision;

		$this->setParam('precision', $precision);
	}

	/**
	 * @return int
	 */
	public function getPrecision(): int
	{
		return (int) $this->getParam('precision', 2);
	}

}
