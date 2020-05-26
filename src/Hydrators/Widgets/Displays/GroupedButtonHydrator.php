<?php declare(strict_types = 1);

/**
 * GroupedButtonHydrator.php
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

namespace FastyBird\UINode\Hydrators\Widgets\Displays;

use FastyBird\NodeWebServer\Exceptions as NodeWebServerExceptions;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Types;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;

/**
 * Grouped button widget display entity hydrator
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class GroupedButtonHydrator extends DisplayHydrator
{

	/** @var string[] */
	protected $attributes = [
		'icon',
	];

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Widgets\Display\GroupedButton::class;
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return Types\WidgetIcons
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	protected function hydrateIconAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): Types\WidgetIcons
	{
		if ($attributes->get('icon') === null || (string) $attributes->get('icon') === '') {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//node.base.messages.missingRequired.heading'),
				$this->translator->translate('//node.base.messages.missingRequired.message'),
				[
					'pointer' => '/data/attributes/icon',
				]
			);
		}

		if (!Types\WidgetIcons::isValidValue($attributes->get('icon'))) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.invalidValue.heading'),
				$this->translator->translate('messages.invalidValue.message'),
				[
					'pointer' => '/data/attributes/icon',
				]
			);
		}

		return Types\WidgetIcons::get($attributes->get('icon'));
	}

}
