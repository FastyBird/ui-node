<?php declare(strict_types = 1);

/**
 * Field.php
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

use Nette;

/**
 * Entity field
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class Field implements IField
{

	use Nette\SmartObject;

	/** @var string */
	private $mappedName;

	/** @var string */
	private $fieldName;

	/** @var bool */
	private $isRequired = false;

	/** @var bool */
	private $isWritable = false;

	public function __construct(
		string $mappedName,
		string $fieldName,
		bool $isRequired,
		bool $isWritable
	) {
		$this->mappedName = $mappedName;
		$this->fieldName = $fieldName;
		$this->isRequired = $isRequired;
		$this->isWritable = $isWritable;
	}

	/**
	 * @return string
	 */
	public function getMappedName(): string
	{
		return $this->mappedName;
	}

	/**
	 * @return string
	 */
	public function getFieldName(): string
	{
		return $this->fieldName;
	}

	/**
	 * @return bool
	 */
	public function isRequired(): bool
	{
		return $this->isRequired;
	}

	/**
	 * @return bool
	 */
	public function isWritable(): bool
	{
		return $this->isWritable;
	}

}
