<?php declare(strict_types = 1);

/**
 * IDashboardsManager.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\UINode\Models\Dashboards;

use FastyBird\UINode\Entities;
use Nette\Utils;

/**
 * Dashboards entities manager interface
 *
 * @package        FastyBird:UINode!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDashboardsManager
{

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Dashboards\IDashboard
	 */
	public function create(
		Utils\ArrayHash $values
	): Entities\Dashboards\IDashboard;

	/**
	 * @param Entities\Dashboards\IDashboard $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Dashboards\IDashboard
	 */
	public function update(
		Entities\Dashboards\IDashboard $entity,
		Utils\ArrayHash $values
	): Entities\Dashboards\IDashboard;

	/**
	 * @param Entities\Dashboards\IDashboard $entity
	 *
	 * @return bool
	 */
	public function delete(
		Entities\Dashboards\IDashboard $entity
	): bool;

}
