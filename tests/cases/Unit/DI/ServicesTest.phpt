<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\NodeLibs\Boot;
use FastyBird\UINode\Controllers;
use FastyBird\UINode\Hydrators;
use FastyBird\UINode\Models;
use FastyBird\UINode\Schemas;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

final class ServicesTest extends BaseTestCase
{

	public function testServicesRegistration(): void
	{
		$configurator = Boot\Bootstrap::boot();
		$configurator->addParameters([
			'database' => [
				'driver' => 'pdo_sqlite',
			],
		]);

		$container = $configurator->createContainer();

		Assert::notNull($container->getByType(Models\Dashboards\DashboardRepository::class));
		Assert::notNull($container->getByType(Models\Groups\GroupRepository::class));
		Assert::notNull($container->getByType(Models\Widgets\WidgetRepository::class));
		Assert::notNull($container->getByType(Models\Widgets\DataSources\DataSourceRepository::class));

		Assert::notNull($container->getByType(Models\Dashboards\DashboardsManager::class));
		Assert::notNull($container->getByType(Models\Groups\GroupsManager::class));
		Assert::notNull($container->getByType(Models\Widgets\WidgetsManager::class));
		Assert::notNull($container->getByType(Models\Widgets\Displays\DisplaysManager::class));
		Assert::notNull($container->getByType(Models\Widgets\DataSources\DataSourcesManager::class));

		Assert::notNull($container->getByType(Controllers\DashboardsV1Controller::class));
		Assert::notNull($container->getByType(Controllers\GroupsV1Controller::class));
		Assert::notNull($container->getByType(Controllers\WidgetsV1Controller::class));
		Assert::notNull($container->getByType(Controllers\DisplayV1Controller::class));
		Assert::notNull($container->getByType(Controllers\DataSourceV1Controller::class));

		Assert::notNull($container->getByType(Schemas\Dashboards\DashboardSchema::class));
		Assert::notNull($container->getByType(Schemas\Groups\GroupSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\AnalogActuatorSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\AnalogSensorSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\DigitalActuatorSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\DigitalSensorSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\AnalogValueSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\ButtonSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\ChartGraphSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\DigitalValueSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\GaugeSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\GroupedButtonSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\Display\SliderSchema::class));
		Assert::notNull($container->getByType(Schemas\Widgets\DataSources\ChannelPropertyDataSourceSchema::class));

		Assert::notNull($container->getByType(Hydrators\Dashboards\DashboardHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Groups\GroupHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\AnalogActuatorWidgetHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\AnalogSensorWidgetHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\DigitalActuatorWidgetHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\DigitalSensorWidgetHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\AnalogValueHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\ButtonHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\ChartGraphHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\DigitalValueHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\GaugeHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\GroupedButtonHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\Displays\SliderHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Widgets\DataSources\ChannelPropertyDataSourceHydrator::class));
	}

}

$test_case = new ServicesTest();
$test_case->run();
