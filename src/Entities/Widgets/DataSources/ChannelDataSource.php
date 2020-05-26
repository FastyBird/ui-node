<?php declare(strict_types = 1);

/**
 * ChannelDataSource.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Entities\Widgets\DataSources;

use Doctrine\ORM\Mapping as ORM;
use FastyBird\UINode\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use Throwable;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fb_widgets_data_sources_channels",
 *     options={
 *       "collate"="utf8mb4_general_ci",
 *       "charset"="utf8mb4",
 *       "comment"="Widget data source connection to channel"
 *     }
 * )
 */
class ChannelDataSource extends DataSource implements IChannelDataSource
{

	/**
	 * @var string
	 *
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @ORM\Column(type="string", name="data_source_id_channel", length=100, nullable=false)
	 */
	private $channel;

	/**
	 * @param string $channel
	 * @param Entities\Widgets\IWidget $widget
	 *
	 * @throws Throwable
	 */
	public function __construct(
		string $channel,
		Entities\Widgets\IWidget $widget
	) {
		parent::__construct($widget);

		$this->channel = $channel;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setChannel(string $channel): void
	{
		$this->channel = $channel;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getChannel(): string
	{
		return $this->channel;
	}

}
