<?php declare(strict_types = 1);

/**
 * GroupSchema.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Schemas\Groups;

use FastyBird\NodeJsonApi\Schemas as NodeJsonApiSchemas;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Router;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Dashboard group entity schema
 *
 * @package          FastyBird:UINode!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Groups\IGroup
 * @phpstan-extends  NodeJsonApiSchemas\JsonApiSchema<T>
 */
final class GroupSchema extends NodeJsonApiSchemas\JsonApiSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/group';

	/**
	 * Define relationships names
	 */
	public const RELATIONSHIPS_WIDGETS = 'widgets';
	public const RELATIONSHIPS_DASHBOARD = 'dashboard';

	/** @var Routing\IRouter */
	protected $router;

	/**
	 * @param Routing\IRouter $router
	 */
	public function __construct(
		Routing\IRouter $router
	) {
		$this->router = $router;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityClass(): string
	{
		return Entities\Groups\Group::class;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	/**
	 * @param Entities\Groups\IGroup $group
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($group, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			'name'     => $group->getName(),
			'comment'  => $group->getComment(),
			'priority' => $group->getPriority(),

			'params' => (array) $group->getParams(),
		];
	}

	/**
	 * @param Entities\Groups\IGroup $group
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getSelfLink($group): JsonApi\Contracts\Schema\LinkInterface
	{
		return new JsonApi\Schema\Link(
			false,
			$this->router->urlFor(
				'dashboard.group',
				[
					Router\Router::URL_ITEM_ID      => $group->getPlainId(),
					Router\Router::URL_DASHBOARD_ID => $group->getDashboard()->getPlainId(),
				]
			),
			false
		);
	}

	/**
	 * @param Entities\Groups\IGroup $group
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($group, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_DASHBOARD => [
				self::RELATIONSHIP_DATA          => $group->getDashboard(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => true,
			],
			self::RELATIONSHIPS_WIDGETS   => [
				self::RELATIONSHIP_DATA          => $group->getWidgets(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => false,
			],
		];
	}

	/**
	 * @param Entities\Groups\IGroup $group
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipRelatedLink($group, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_DASHBOARD) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'dashboard',
					[
						Router\Router::URL_ITEM_ID => $group->getDashboard()->getPlainId(),
					]
				),
				false
			);
		}

		return parent::getRelationshipRelatedLink($group, $name);
	}

	/**
	 * @param Entities\Groups\IGroup $group
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $group
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipSelfLink($group, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if (
			$name === self::RELATIONSHIPS_DASHBOARD
			|| $name === self::RELATIONSHIPS_WIDGETS
		) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'dashboard.group.relationship',
					[
						Router\Router::URL_ITEM_ID      => $group->getPlainId(),
						Router\Router::URL_DASHBOARD_ID => $group->getDashboard()->getPlainId(),
						Router\Router::RELATION_ENTITY  => $name,
					]
				),
				false
			);
		}

		return parent::getRelationshipSelfLink($group, $name);
	}

}
