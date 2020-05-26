<?php declare(strict_types = 1);

/**
 * TWidgetFinder.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Controllers\Finders;

use FastyBird\NodeWebServer\Exceptions as NodeWebServerExceptions;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Models;
use FastyBird\UINode\Queries;
use Fig\Http\Message\StatusCodeInterface;
use Nette\Localization;
use Ramsey\Uuid;

/**
 * @property-read Localization\ITranslator $translator
 * @property-read Models\Widgets\IWidgetRepository $widgetRepository
 */
trait TWidgetFinder
{

	/**
	 * @param string $id
	 *
	 * @return Entities\Widgets\IWidget
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	protected function findWidget(string $id): Entities\Widgets\IWidget
	{
		try {
			$findQuery = new Queries\FindWidgetsQuery();
			$findQuery->byId(Uuid\Uuid::fromString($id));

			$widget = $this->widgetRepository->findOneBy($findQuery);

			if ($widget === null) {
				throw new NodeWebServerExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_NOT_FOUND,
					$this->translator->translate('//node.base.messages.widgetNotFound.heading'),
					$this->translator->translate('//node.base.messages.widgetNotFound.message')
				);
			}

		} catch (Uuid\Exception\InvalidUuidStringException $ex) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//node.base.messages.widgetNotFound.heading'),
				$this->translator->translate('//node.base.messages.widgetNotFound.message')
			);
		}

		return $widget;
	}

}
