<?php declare(strict_types = 1);

/**
 * DashboardSchema.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           13.04.19
 */

namespace FastyBird\UINode\Schemas\Dashboards;

use FastyBird\UINode\Entities;
use FastyBird\UINode\Router;
use FastyBird\UINode\Schemas;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Dashboard entity schema
 *
 * @package          FastyBird:UINode!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Dashboards\IDashboard
 * @phpstan-extends  Schemas\JsonApiSchema<T>
 */
final class DashboardSchema extends Schemas\JsonApiSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/dashboard';

	/**
	 * Define relationships names
	 */
	public const RELATIONSHIPS_GROUPS = 'groups';

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
		return Entities\Dashboards\Dashboard::class;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	/**
	 * @param Entities\Dashboards\IDashboard $dashboard
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|int|string[]|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($dashboard, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			'name'     => $dashboard->getName(),
			'comment'  => $dashboard->getComment(),
			'priority' => $dashboard->getPriority(),

			'params' => (array) $dashboard->getParams(),
		];
	}

	/**
	 * @param Entities\Dashboards\IDashboard $dashboard
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getSelfLink($dashboard): JsonApi\Contracts\Schema\LinkInterface
	{
		return new JsonApi\Schema\Link(
			false,
			$this->router->urlFor(
				'dashboard',
				[
					Router\Router::URL_ITEM_ID => $dashboard->getPlainId(),
				]
			),
			false
		);
	}

	/**
	 * @param Entities\Dashboards\IDashboard $dashboard
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($dashboard, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_GROUPS => [
				self::RELATIONSHIP_DATA          => $dashboard->getGroups(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => true,
			],
		];
	}

	/**
	 * @param Entities\Dashboards\IDashboard $dashboard
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipRelatedLink($dashboard, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_GROUPS) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'dashboard.groups',
					[
						Router\Router::URL_DASHBOARD_ID => $dashboard->getPlainId(),
					]
				),
				true,
				[
					'count' => count($dashboard->getGroups()),
				]
			);
		}

		return parent::getRelationshipRelatedLink($dashboard, $name);
	}

	/**
	 * @param Entities\Dashboards\IDashboard $dashboard
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $dashboard
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipSelfLink($dashboard, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_GROUPS) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'dashboard.relationship',
					[
						Router\Router::URL_ITEM_ID     => $dashboard->getPlainId(),
						Router\Router::RELATION_ENTITY => $name,
					]
				),
				false
			);
		}

		return parent::getRelationshipSelfLink($dashboard, $name);
	}

}
