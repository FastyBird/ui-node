<?php declare(strict_types = 1);

/**
 * WidgetHydrator.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           26.05.20
 */

namespace FastyBird\UINode\Hydrators\Widgets;

use Contributte\Translation;
use Doctrine\Common;
use FastyBird\NodeDatabase\Hydrators as NodeDatabaseHydrators;
use FastyBird\NodeWebServer\Exceptions as NodeWebServerExceptions;
use FastyBird\UINode\Entities;
use FastyBird\UINode\Models;
use FastyBird\UINode\Queries;
use FastyBird\UINode\Schemas;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;
use Ramsey\Uuid;

/**
 * Widget entity hydrator
 *
 * @package        FastyBird:UINode!
 * @subpackage     Hydrators
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class WidgetHydrator extends NodeDatabaseHydrators\Hydrator
{

	/** @var string */
	protected $entityIdentifier = self::IDENTIFIER_KEY;

	/** @var string[] */
	protected $attributes = [
		'name',
	];

	/** @var string[] */
	protected $relationships = [
		0 => Schemas\Widgets\WidgetSchema::RELATIONSHIPS_DISPLAY,
		1 => Schemas\Widgets\WidgetSchema::RELATIONSHIPS_GROUPS,
		Schemas\Widgets\WidgetSchema::RELATIONSHIPS_DATA_SOURCES => 'dataSources',
	];

	/** @var Models\Groups\IGroupRepository */
	private $groupRepository;

	/** @var string */
	protected $translationDomain = 'node.widgets';

	/**
	 * @param Models\Groups\IGroupRepository $groupRepository
	 * @param Common\Persistence\ManagerRegistry $managerRegistry
	 * @param Translation\Translator $translator
	 *
	 * @throws Common\Annotations\AnnotationException
	 */
	public function __construct(
		Models\Groups\IGroupRepository $groupRepository,
		Common\Persistence\ManagerRegistry $managerRegistry,
		Translation\Translator $translator
	) {
		parent::__construct($managerRegistry, $translator);

		$this->groupRepository = $groupRepository;
	}

	/**
	 * @param JsonAPIDocument\Objects\IRelationship<mixed> $relationship
	 * @param JsonAPIDocument\Objects\IResourceObjectCollection<JsonAPIDocument\Objects\IResourceObject>|null $included
	 *
	 * @return mixed[]|null
	 */
	protected function hydrateDisplayRelationship(
		JsonAPIDocument\Objects\IRelationship $relationship,
		?JsonAPIDocument\Objects\IResourceObjectCollection $included
	): ?array {
		if (!$relationship->isHasOne()) {
			return null;
		}

		if ($included !== null) {
			/** @var JsonAPIDocument\Objects\IResourceObject<mixed> $item */
			foreach ($included->getAll() as $item) {
				if (
					$relationship->getIdentifier() !== null
					&& $item->getIdentifier()->getId() === $relationship->getIdentifier()->getId()
				) {
					$result = $this->buildDisplay($item->getType(), $item->getAttributes());

					if ($result !== null) {
						return $result;
					}
				}
			}
		}

		if (!$relationship->getData() instanceof JsonAPIDocument\Objects\IResourceIdentifier) {
			return null;
		}

		$result = $this->buildDisplay($relationship->getData()->getType(), $relationship);

		return $result;
	}

	/**
	 * @param JsonAPIDocument\Objects\IRelationship<mixed> $relationship
	 * @param JsonAPIDocument\Objects\IResourceObjectCollection<JsonAPIDocument\Objects\IResourceObject>|null $included
	 *
	 * @return mixed[]
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	protected function hydrateDataSourcesRelationship(
		JsonAPIDocument\Objects\IRelationship $relationship,
		?JsonAPIDocument\Objects\IResourceObjectCollection $included
	): array {
		if ($included === null) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.missingDataSource.heading'),
				$this->translator->translate('messages.missingDataSource.message'),
				[
					'pointer' => '/data/relationships/data_sources/data/id',
				]
			);
		}

		$dataSources = [];

		foreach ($relationship as $dataSourcesRelation) {
			/** @var JsonAPIDocument\Objects\IResourceObject<mixed> $dataSourceRelation */
			foreach ($dataSourcesRelation as $dataSourceRelation) {
				if ($dataSourceRelation->getType() === Schemas\Widgets\DataSources\ChannelPropertyDataSourceSchema::SCHEMA_TYPE) {
					foreach ($included->getAll() as $item) {
						if ($item->getIdentifier()->getId() === $dataSourceRelation->getIdentifier()->getId()) {
							$dataSources[] = [
								'entity'  => Entities\Widgets\DataSources\ChannelPropertyDataSource::class,
								'channel' => $item->get('channel'),
							];
						}
					}
				}
			}
		}

		if ($dataSources === []) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.missingDataSource.heading'),
				$this->translator->translate('messages.missingDataSource.message'),
				[
					'pointer' => '/data/relationships/data_sources/data/id',
				]
			);
		}

		return $dataSources;
	}

	/**
	 * @param JsonAPIDocument\Objects\IRelationship<mixed> $relationship
	 * @param JsonAPIDocument\Objects\IResourceObjectCollection<JsonAPIDocument\Objects\IResourceObject>|null $included
	 *
	 * @return mixed[]|null
	 *
	 * @throws NodeWebServerExceptions\IJsonApiException
	 */
	protected function hydrateGroupsRelationship(
		JsonAPIDocument\Objects\IRelationship $relationship,
		?JsonAPIDocument\Objects\IResourceObjectCollection $included
	): ?array {
		if (!$relationship->isHasMany()) {
			return null;
		}

		$groups = [];

		foreach ($relationship as $groupsRelation) {
			/** @var JsonAPIDocument\Objects\IResourceObject<JsonAPIDocument\Objects\IStandardObject> $groupRelation */
			foreach ($groupsRelation as $groupRelation) {
				try {
					$findQuery = new Queries\FindGroupsQuery();
					$findQuery->byId(Uuid\Uuid::fromString($groupRelation->getIdentifier()->getId()));

					$group = $this->groupRepository->findOneBy($findQuery);

					if ($group !== null) {
						$groups[] = $group;
					}

					break;

				} catch (Uuid\Exception\InvalidUuidStringException $ex) {
					throw new NodeWebServerExceptions\JsonApiErrorException(
						StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
						$this->translator->translate('//node.base.messages.invalid.heading'),
						$this->translator->translate('//node.base.messages.invalid.message'),
						[
							'pointer' => '/data/relationships/groups/data/id',
						]
					);
				}
			}
		}

		if ($groups === []) {
			throw new NodeWebServerExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.missingGroups.heading'),
				$this->translator->translate('messages.missingGroups.message'),
				[
					'pointer' => '/data/relationships/groups/data/id',
				]
			);
		}

		return $groups;
	}

	/**
	 * @param string $type
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return mixed[]|null
	 */
	private function buildDisplay(
		string $type,
		JsonAPIDocument\Objects\IStandardObject $attributes
	): ?array {
		switch ($type) {
			case Schemas\Widgets\Display\DigitalValueSchema::SCHEMA_TYPE:
				$entityMapping = $this->mapEntity(Entities\Widgets\Display\DigitalValue::class);

				$display = $this->hydrateAttributes(
					Entities\Widgets\Display\DigitalValue::class,
					$attributes,
					$entityMapping,
					null,
					null
				);

				$display['entity'] = Entities\Widgets\Display\DigitalValue::class;

				return $display;

			case Schemas\Widgets\Display\AnalogValueSchema::SCHEMA_TYPE:
				$entityMapping = $this->mapEntity(Entities\Widgets\Display\AnalogValue::class);

				$display = $this->hydrateAttributes(
					Entities\Widgets\Display\AnalogValue::class,
					$attributes,
					$entityMapping,
					null,
					null
				);

				$display['entity'] = Entities\Widgets\Display\AnalogValue::class;

				return $display;

			case Schemas\Widgets\Display\ButtonSchema::SCHEMA_TYPE:
				$entityMapping = $this->mapEntity(Entities\Widgets\Display\Button::class);

				$display = $this->hydrateAttributes(
					Entities\Widgets\Display\Button::class,
					$attributes,
					$entityMapping,
					null,
					null
				);

				$display['entity'] = Entities\Widgets\Display\Button::class;

				return $display;

			case Schemas\Widgets\Display\GroupedButtonSchema::SCHEMA_TYPE:
				$entityMapping = $this->mapEntity(Entities\Widgets\Display\GroupedButton::class);

				$display = $this->hydrateAttributes(
					Entities\Widgets\Display\GroupedButton::class,
					$attributes,
					$entityMapping,
					null,
					null
				);

				$display['entity'] = Entities\Widgets\Display\GroupedButton::class;

				return $display;
		}

		return null;
	}

}
