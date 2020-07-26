<?php declare(strict_types = 1);

/**
 * TDashboardFinder.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Controllers\Finders;

use FastyBird\NodeJsonApi\Exceptions as NodeJsonApiExceptions;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Models;
use FastyBird\UINode\Queries;
use Fig\Http\Message\StatusCodeInterface;
use Nette\Localization;
use Ramsey\Uuid;

/**
 * @property-read Localization\ITranslator $translator
 * @property-read Models\Dashboards\IDashboardRepository $dashboardRepository
 */
trait TDashboardFinder
{

	/**
	 * @param string $id
	 *
	 * @return Entities\Dashboards\IDashboard
	 *
	 * @throws NodeJsonApiExceptions\IJsonApiException
	 */
	protected function findDashboard(string $id): Entities\Dashboards\IDashboard
	{
		try {
			$findQuery = new Queries\FindDashboardsQuery();
			$findQuery->byId(Uuid\Uuid::fromString($id));

			$dashboard = $this->dashboardRepository->findOneBy($findQuery);

			if ($dashboard === null) {
				throw new NodeJsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_NOT_FOUND,
					$this->translator->translate('//node.base.messages.dashboardNotFound.heading'),
					$this->translator->translate('//node.base.messages.dashboardNotFound.message')
				);
			}

		} catch (Uuid\Exception\InvalidUuidStringException $ex) {
			throw new NodeJsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//node.base.messages.dashboardNotFound.heading'),
				$this->translator->translate('//node.base.messages.dashboardNotFound.message')
			);
		}

		return $dashboard;
	}

}
