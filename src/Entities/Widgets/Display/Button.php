<?php declare(strict_types = 1);

/**
 * Button.php
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

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Button extends Display implements IButton
{

}
