<?php declare(strict_types = 1);

/**
 * ChannelDataSourceSchema.php
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

namespace FastyBird\UINode\Schemas\Widgets\DataSources;

use FastyBird\UINode\Entities;
use FastyBird\UINode\Router;
use FastyBird\UINode\Schemas;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Channel data source entity schema
 *
 * @package          FastyBird:UINode!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Widgets\DataSources\IChannelDataSource
 * @phpstan-extends  Schemas\JsonApiSchema<T>
 */
final class ChannelDataSourceSchema extends Schemas\JsonApiSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/widget-channel-data-source';

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
		return Entities\Widgets\Display\DigitalValue::class;
	}

	/**
	 * @param Entities\Widgets\DataSources\IChannelDataSource $dataSource
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|string[]|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($dataSource, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			'channel' => $dataSource->getChannel(),
		];
	}

	/**
	 * @param Entities\Widgets\DataSources\IChannelDataSource $dataSource
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
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
	 * @param Entities\Widgets\DataSources\IChannelDataSource $dataSource
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($dataSource, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_WIDGET => [
				self::RELATIONSHIP_DATA          => $dataSource->getWidget(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => false,
			],
		];
	}

	/**
	 * @param Entities\Widgets\DataSources\IChannelDataSource $dataSource
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
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

}
