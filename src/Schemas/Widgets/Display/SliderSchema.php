<?php declare(strict_types = 1);

/**
 * SliderSchema.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Schemas\Widgets\Display;

use FastyBird\UINode\Entities;
use Neomerx\JsonApi;

/**
 * Analog value widget display entity schema
 *
 * @package         FastyBird:UINode!
 * @subpackage      Schemas
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends DisplaySchema<Entities\Widgets\Display\ISlider>
 */
final class SliderSchema extends DisplaySchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/widget-display-slider';

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityClass(): string
	{
		return Entities\Widgets\Display\Slider::class;
	}

	/**
	 * @param Entities\Widgets\Display\ISlider $display
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|int|float|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($display, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return array_merge((array) parent::getAttributes($display, $context), [
			'minimum_value' => $display->getMinimumValue(),
			'maximum_value' => $display->getMaximumValue(),
			'precision'     => $display->getPrecision(),
			'step_value'    => $display->getStepValue(),
		]);
	}

}
