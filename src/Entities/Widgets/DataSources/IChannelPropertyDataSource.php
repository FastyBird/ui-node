<?php declare(strict_types = 1);

/**
 * IChannelPropertyDataSource.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Entities\Widgets\DataSources;

/**
 * Widget thing channel data source entity interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IChannelPropertyDataSource extends IDataSource
{

	/**
	 * @param string $channel
	 *
	 * @return void
	 */
	public function setChannel(string $channel): void;

	/**
	 * @return string
	 */
	public function getChannel(): string;

	/**
	 * @param string $property
	 *
	 * @return void
	 */
	public function setProperty(string $property): void;

	/**
	 * @return string
	 */
	public function getProperty(): string;

}
