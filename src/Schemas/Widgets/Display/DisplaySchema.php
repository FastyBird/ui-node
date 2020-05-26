<?php declare(strict_types = 1);

/**
 * DisplaySchema.php
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
use FastyBird\UINode\Router;
use FastyBird\UINode\Schemas;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Widget display entity schema constructor
 *
 * @package          FastyBird:UINode!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Widgets\Display\IDisplay
 * @phpstan-extends  Schemas\JsonApiSchema<T>
 */
abstract class DisplaySchema extends Schemas\JsonApiSchema
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
	 * @param Entities\Widgets\Display\IDisplay $display
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($display, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [];
	}

	/**
	 * @param Entities\Widgets\Display\IDisplay $display
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getSelfLink($display): JsonApi\Contracts\Schema\LinkInterface
	{
		return new JsonApi\Schema\Link(
			false,
			$this->router->urlFor(
				'widget.display',
				[
					Router\Router::URL_ITEM_ID   => $display->getPlainId(),
					Router\Router::URL_WIDGET_ID => $display->getWidget()->getPlainId(),
				]
			),
			false
		);
	}

	/**
	 * @param Entities\Widgets\Display\IDisplay $display
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($display, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_WIDGET => [
				self::RELATIONSHIP_DATA          => $display->getWidget(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => false,
			],
		];
	}

	/**
	 * @param Entities\Widgets\Display\IDisplay $display
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipRelatedLink($display, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_WIDGET) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'widget',
					[
						Router\Router::URL_ITEM_ID => $display->getWidget()->getPlainId(),
					]
				),
				false
			);
		}

		return parent::getRelationshipRelatedLink($display, $name);
	}

}
