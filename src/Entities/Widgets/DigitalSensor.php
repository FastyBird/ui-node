<?php declare(strict_types = 1);

/**
 * DigitalSensor.php
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

namespace FastyBird\UINode\Entities\Widgets;

use Doctrine\ORM\Mapping as ORM;
use FastyBird\UINode\Entities;

/**
 * @ORM\Entity
 */
class DigitalSensor extends Sensor implements IDigitalSensor
{

	/** @var string[] */
	protected $allowedDisplay = [
		Entities\Widgets\Display\IDigitalValue::class,
		Entities\Widgets\Display\IChartGraph::class,
	];

}
