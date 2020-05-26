<?php declare(strict_types = 1);

/**
 * TMaximumValue.php
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

use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * Display maximum value parameter
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @method void setParam(string $key, $value = null)
 * @method mixed getParam(string $key, $default = null)
 */
trait TMaximumValue
{

	/**
	 * @var float|null
	 *
	 * @IPubDoctrine\Crud(is={"writable"})
	 */
	protected $maximumValue;

	/**
	 * @param float|null $maximumValue
	 *
	 * @return void
	 */
	public function setMaximumValue(?float $maximumValue): void
	{
		$this->maximumValue = $maximumValue;

		$this->setParam('maximumValue', $maximumValue);
	}

	/**
	 * @return float|null
	 */
	public function getMaximumValue(): ?float
	{
		$value = $this->getParam('maximumValue');

		return $value === null ? null : (float) $value;
	}

}
