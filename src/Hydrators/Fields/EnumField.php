<?php declare(strict_types = 1);

/**
 * EnumField.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Hydrators\Fields;

use Consistence;
use IPub\JsonAPIDocument;

/**
 * Entity consistence enum field
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class EnumField extends Field
{

	/** @var string */
	private $typeClass;

	/** @var bool */
	private $isNullable = true;

	public function __construct(
		string $typeClass,
		bool $isNullable,
		string $mappedName,
		string $fieldName,
		bool $isRequired,
		bool $isWritable
	) {
		parent::__construct($mappedName, $fieldName, $isRequired, $isWritable);

		$this->typeClass = $typeClass;
		$this->isNullable = $isNullable;
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return Consistence\Enum\Enum|null
	 */
	public function getValue(JsonAPIDocument\Objects\IStandardObject $attributes): ?Consistence\Enum\Enum
	{
		$value = $attributes->get($this->getMappedName());

		$callable = [$this->typeClass, 'get'];

		if (is_callable($callable)) {
			return $value !== null ? call_user_func($callable, $value) : null;
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function isNullable(): bool
	{
		return $this->isNullable;
	}

}
