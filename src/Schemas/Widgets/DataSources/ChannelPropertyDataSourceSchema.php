<?php declare(strict_types = 1);

/**
 * ChannelPropertyDataSourceSchema.php
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
use Neomerx\JsonApi;

/**
 * Channel data source entity schema
 *
 * @package          FastyBird:UINode!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Widgets\DataSources\IChannelPropertyDataSource
 * @phpstan-extends  DataSourceSchema<T>
 */
final class ChannelPropertyDataSourceSchema extends DataSourceSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'ui-node/widget-channel-data-source';

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
		return Entities\Widgets\DataSources\ChannelPropertyDataSource::class;
	}

	/**
	 * @param Entities\Widgets\DataSources\IChannelPropertyDataSource $dataSource
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|string[]|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($dataSource, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return array_merge(
			(array) parent::getAttributes($dataSource, $context),
			[
				'channel'  => $dataSource->getChannel(),
				'property' => $dataSource->getProperty(),
			]
		);
	}

}
