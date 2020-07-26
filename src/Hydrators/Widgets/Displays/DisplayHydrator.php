<?php declare(strict_types = 1);

/**
 * DisplayHydrator.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Hydrators\Widgets\Displays;

use FastyBird\NodeJsonApi\Exceptions as NodeJsonApiExceptions;
use FastyBird\NodeJsonApi\Hydrators as NodeJsonApiHydrators;
use FastyBird\UINode\Schemas;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;

/**
 * Widget entity hydrator
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class DisplayHydrator extends NodeJsonApiHydrators\Hydrator
{

	/** @var string */
	protected $entityIdentifier = self::IDENTIFIER_KEY;

	/** @var string[] */
	protected $relationships = [
		Schemas\Widgets\Display\DisplaySchema::RELATIONSHIPS_WIDGET,
	];

	/** @var string */
	protected $translationDomain = 'node.display';

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return int
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	protected function hydratePrecisionAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): int
	{
		if ($attributes->get('precision') === null || (string) $attributes->get('precision') === '') {
			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => '/data/attributes/precision',
				]
			);
		}

		return (int) $attributes->get('precision');
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return float
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	protected function hydrateMinimumValueAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): float
	{
		if ($attributes->get('minimum_value') === null || (string) $attributes->get('minimum_value') === '') {
			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => '/data/attributes/minimum_value',
				]
			);
		}

		return (float) $attributes->get('minimum_value');
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return float
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	protected function hydrateMaximumValueAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): float
	{
		if ($attributes->get('maximum_value') === null || (string) $attributes->get('maximum_value') === '') {
			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => '/data/attributes/maximum_value',
				]
			);
		}

		return (float) $attributes->get('maximum_value');
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return float
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	protected function hydrateStepValueAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): float
	{
		if ($attributes->get('step_value') === null || (string) $attributes->get('step_value') === '') {
			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => '/data/attributes/step_value',
				]
			);
		}

		return (float) $attributes->get('step_value');
	}

}
