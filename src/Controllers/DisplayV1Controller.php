<?php declare(strict_types = 1);

/**
 * DisplayV1Controller.php
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

namespace FastyBird\UINode\Controllers;

use Doctrine;
use FastyBird\NodeWebServer\Exceptions as NodeWebServerExceptions;
use FastyBird\NodeWebServer\Http as NodeWebServerHttp;
use FastyBird\UINode\Controllers;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Hydrators;
use FastyBird\UINode\Models;
use FastyBird\UINode\Router;
use FastyBird\UINode\Schemas;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message;
use Throwable;

/**
 * API widgets display controller
 *
 * @package        FastyBird:UINode!
 * @subpackage     Controllers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DisplayV1Controller extends BaseV1Controller
{

	use Controllers\Finders\TWidgetFinder;

	/** @var Models\Widgets\Displays\IDisplaysManager */
	private $displaysManager;

	/** @var Hydrators\Widgets\Displays\AnalogValueHydrator */
	private $analogValueHydrator;

	/** @var Hydrators\Widgets\Displays\ButtonHydrator */
	private $buttonHydrator;

	/** @var Hydrators\Widgets\Displays\ChartGraphHydrator */
	private $chartGraphHydrator;

	/** @var Hydrators\Widgets\Displays\DigitalValueHydrator */
	private $digitalValueHydrator;

	/** @var Hydrators\Widgets\Displays\GaugeHydrator */
	private $gaugeHydrator;

	/** @var Hydrators\Widgets\Displays\GroupedButtonHydrator */
	private $groupedButtonHydrator;

	/** @var Hydrators\Widgets\Displays\SliderHydrator */
	private $sliderHydrator;

	/** @var Models\Widgets\IWidgetRepository */
	protected $widgetRepository;

	/** @var string */
	protected $translationDomain = 'node.display';

	/**
	 * @param Models\Widgets\Displays\IDisplaysManager $displaysManager
	 * @param Models\Widgets\IWidgetRepository $widgetRepository
	 * @param Hydrators\Widgets\Displays\AnalogValueHydrator $analogValueHydrator
	 * @param Hydrators\Widgets\Displays\ButtonHydrator $buttonHydrator
	 * @param Hydrators\Widgets\Displays\ChartGraphHydrator $chartGraphHydrator
	 * @param Hydrators\Widgets\Displays\DigitalValueHydrator $digitalValueHydrator
	 * @param Hydrators\Widgets\Displays\GaugeHydrator $gaugeHydrator
	 * @param Hydrators\Widgets\Displays\GroupedButtonHydrator $groupedButtonHydrator
	 * @param Hydrators\Widgets\Displays\SliderHydrator $sliderHydrator
	 */
	public function __construct(
		Models\Widgets\Displays\IDisplaysManager $displaysManager,
		Models\Widgets\IWidgetRepository $widgetRepository,
		Hydrators\Widgets\Displays\AnalogValueHydrator $analogValueHydrator,
		Hydrators\Widgets\Displays\ButtonHydrator $buttonHydrator,
		Hydrators\Widgets\Displays\ChartGraphHydrator $chartGraphHydrator,
		Hydrators\Widgets\Displays\DigitalValueHydrator $digitalValueHydrator,
		Hydrators\Widgets\Displays\GaugeHydrator $gaugeHydrator,
		Hydrators\Widgets\Displays\GroupedButtonHydrator $groupedButtonHydrator,
		Hydrators\Widgets\Displays\SliderHydrator $sliderHydrator
	) {
		$this->displaysManager = $displaysManager;
		$this->widgetRepository = $widgetRepository;
		$this->analogValueHydrator = $analogValueHydrator;
		$this->buttonHydrator = $buttonHydrator;
		$this->chartGraphHydrator = $chartGraphHydrator;
		$this->digitalValueHydrator = $digitalValueHydrator;
		$this->gaugeHydrator = $gaugeHydrator;
		$this->groupedButtonHydrator = $groupedButtonHydrator;
		$this->sliderHydrator = $sliderHydrator;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	public function read(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		// At first, try to load widget
		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_WIDGET_ID));

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($widget->getDisplay()));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 */
	public function update(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		$document = $this->createDocument($request);

		// At first, try to load widget
		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_WIDGET_ID));

		$display = $widget->getDisplay();

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			if (
				$document->getResource()->getType() === Schemas\Widgets\Display\AnalogValueSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\IAnalogValue
			) {
				$updateDisplayData = $this->analogValueHydrator->hydrate($document, $display);

			} elseif (
				$document->getResource()->getType() === Schemas\Widgets\Display\ButtonSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\IButton
			) {
				$updateDisplayData = $this->buttonHydrator->hydrate($document, $display);

			} elseif (
				$document->getResource()->getType() === Schemas\Widgets\Display\ChartGraphSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\IChartGraph
			) {
				$updateDisplayData = $this->chartGraphHydrator->hydrate($document, $display);

			} elseif (
				$document->getResource()->getType() === Schemas\Widgets\Display\DigitalValueSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\IDigitalValue
			) {
				$updateDisplayData = $this->digitalValueHydrator->hydrate($document, $display);

			} elseif (
				$document->getResource()->getType() === Schemas\Widgets\Display\GaugeSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\IGauge
			) {
				$updateDisplayData = $this->gaugeHydrator->hydrate($document, $display);

			} elseif (
				$document->getResource()->getType() === Schemas\Widgets\Display\GroupedButtonSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\IGroupedButton
			) {
				$updateDisplayData = $this->groupedButtonHydrator->hydrate($document, $display);

			} elseif (
				$document->getResource()->getType() === Schemas\Widgets\Display\SliderSchema::SCHEMA_TYPE
				&& $display instanceof Entities\Widgets\Display\ISlider
			) {
				$updateDisplayData = $this->sliderHydrator->hydrate($document, $display);

			} else {
				throw new NodeWebServerExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('messages.invalidType.heading'),
					$this->translator->translate('messages.invalidType.message'),
					[
						'pointer' => '/data/type',
					]
				);
			}

			$display = $this->displaysManager->update($display, $updateDisplayData);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (NodeWebServerExceptions\IJsonApiException $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			throw $ex;

		} catch (Throwable $ex) {
			// Revert all changes when error occur
			$this->getOrmConnection()->rollBack();

			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.notUpdated.heading'),
				$this->translator->translate('messages.notUpdated.message')
			);
		}

		return $response
			->withEntity(NodeWebServerHttp\ScalarEntity::from($display));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param NodeWebServerHttp\Response $response
	 *
	 * @return NodeWebServerHttp\Response
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	public function readRelationship(
		Message\ServerRequestInterface $request,
		NodeWebServerHttp\Response $response
	): NodeWebServerHttp\Response {
		// At first, try to load widget
		$widget = $this->findWidget($request->getAttribute(Router\Router::URL_WIDGET_ID));

		$relationEntity = strtolower($request->getAttribute(Router\Router::RELATION_ENTITY));

		if ($relationEntity === Schemas\Widgets\Display\DisplaySchema::RELATIONSHIPS_WIDGET) {
			return $response
				->withEntity(NodeWebServerHttp\ScalarEntity::from($widget));
		}

		$this->throwUnknownRelation($relationEntity);

		return $response;
	}

}
