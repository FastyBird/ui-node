<?php declare(strict_types = 1);

/**
 * GroupHydrator.php
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

namespace FastyBird\UINode\Hydrators\Groups;

use FastyBird\NodeJsonApi\Hydrators as NodeJsonApiHydrators;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Schemas;
use IPub\JsonAPIDocument;

/**
 * Dashboard group entity hydrator
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class GroupHydrator extends NodeJsonApiHydrators\Hydrator
{

	/** @var string */
	protected $entityIdentifier = self::IDENTIFIER_KEY;

	/** @var string[] */
	protected $attributes = [
		'name',
		'comment',
	];

	/** @var string[] */
	protected $relationships = [
		Schemas\Groups\GroupSchema::RELATIONSHIPS_DASHBOARD,
	];

	/** @var string */
	protected $translationDomain = 'node.groups';

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Groups\Group::class;
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return string|null
	 */
	protected function hydrateCommentAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): ?string
	{
		if ($attributes->get('comment') === null || (string) $attributes->get('comment') === '') {
			return null;
		}

		return (string) $attributes->get('comment');
	}

}
