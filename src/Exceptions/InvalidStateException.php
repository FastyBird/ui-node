<?php declare(strict_types = 1);

/**
 * InvalidStateException.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Exceptions;

use RuntimeException;

class InvalidStateException extends RuntimeException implements IException
{

}
