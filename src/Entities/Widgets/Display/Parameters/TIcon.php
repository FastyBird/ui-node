<?php declare(strict_types = 1);

/**
 * TIcon.php
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

use FastyBird\UINode\Types;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;

/**
 * Display icon parameter
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @method void setParam(string $key, $value = null)
 * @method mixed getParam(string $key, $default = null)
 */
trait TIcon
{

	/**
	 * @var Types\WidgetIconType|null
	 *
	 * @IPubDoctrine\Crud(is={"writable"})
	 */
	protected $icon;

	/**
	 * @param Types\WidgetIconType|null $icon
	 *
	 * @return void
	 */
	public function setIcon(?Types\WidgetIconType $icon): void
	{
		$this->icon = $icon;

		if ($icon !== null) {
			$this->setParam('icon', $icon->getValue());

		} else {
			$this->setParam('icon', null);
		}
	}

	/**
	 * @return Types\WidgetIconType|null
	 */
	public function getIcon(): ?Types\WidgetIconType
	{
		$value = $this->getParam('icon');

		return $value === null ? null : Types\WidgetIconType::get($value);
	}

}
