<?php declare(strict_types = 1);

/**
 * DisplaysManager.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Models\Widgets\Displays;

use FastyBird\UINode\Entities;
use FastyBird\UINode\Models;
use IPub\DoctrineCrud\Crud;
use Nette;
use Nette\Utils;

/**
 * Widgets displays entities manager
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class DisplaysManager implements IDisplaysManager
{

	use Nette\SmartObject;

	/** @var Crud\IEntityCrud */
	private $entityCrud;

	public function __construct(
		Crud\IEntityCrud $entityCrud
	) {
		// Entity CRUD for handling entities
		$this->entityCrud = $entityCrud;
	}

	/**
	 * {@inheritDoc}
	 */
	public function update(
		Entities\Widgets\Display\IDisplay $entity,
		Utils\ArrayHash $values
	): Entities\Widgets\Display\IDisplay {
		/** @var Entities\Widgets\Display\IDisplay $entity */
		$entity = $this->entityCrud->getEntityUpdater()->update($values, $entity);

		return $entity;
	}

}
