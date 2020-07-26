<?php declare(strict_types = 1);

/**
 * DataSourceSchema.php
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

namespace FastyBird\UINode\Schemas\Widgets\DataSources;

use FastyBird\NodeJsonApi\Schemas as NodeJsonApiSchemas;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Router;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Data source entity schema
 *
 * @package          FastyBird:UINode!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Widgets\DataSources\IDataSource
 * @phpstan-extends  NodeJsonApiSchemas\JsonApiSchema<T>
 */
abstract class DataSourceSchema extends NodeJsonApiSchemas\JsonApiSchema
{

	/**
	 * Define relationships names
	 */
	public const RELATIONSHIPS_WIDGET = 'widget';

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
	 * @param Entities\Widgets\DataSources\IDataSource $dataSource
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpstan-param T $dataSource
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($dataSource, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [];
	}

	/**
	 * @param Entities\Widgets\DataSources\IDataSource $dataSource
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $dataSource
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getSelfLink($dataSource): JsonApi\Contracts\Schema\LinkInterface
	{
		return new JsonApi\Schema\Link(
			false,
			$this->router->urlFor(
				'widget.data-source',
				[
					Router\Router::URL_ITEM_ID   => $dataSource->getPlainId(),
					Router\Router::URL_WIDGET_ID => $dataSource->getWidget()->getPlainId(),
				]
			),
			false
		);
	}

	/**
	 * @param Entities\Widgets\DataSources\IDataSource $dataSource
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpstan-param T $dataSource
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($dataSource, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_WIDGET => [
				self::RELATIONSHIP_DATA          => $dataSource->getWidget(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => true,
			],
		];
	}

	/**
	 * @param Entities\Widgets\DataSources\IDataSource $dataSource
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $dataSource
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipRelatedLink($dataSource, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_WIDGET) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'widget',
					[
						Router\Router::URL_ITEM_ID => $dataSource->getWidget()->getPlainId(),
					]
				),
				false
			);
		}

		return parent::getRelationshipRelatedLink($dataSource, $name);
	}

	/**
	 * @param Entities\Widgets\DataSources\IDataSource $dataSource
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $dataSource
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipSelfLink($dataSource, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_WIDGET) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'widget.display.relationship',
					[
						Router\Router::URL_ITEM_ID     => $dataSource->getPlainId(),
						Router\Router::URL_WIDGET_ID   => $dataSource->getWidget()->getPlainId(),
						Router\Router::RELATION_ENTITY => $name,
					]
				),
				false
			);
		}

		return parent::getRelationshipSelfLink($dataSource, $name);
	}

}
