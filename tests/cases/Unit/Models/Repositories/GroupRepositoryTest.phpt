<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\UINode\Entities;
use FastyBird\UINode\Models;
use FastyBird\UINode\Queries;
use IPub\DoctrineOrmQuery;
use Ramsey\Uuid;
use Tester\Assert;

require_once __DIR__ . '/../../../../bootstrap.php';
require_once __DIR__ . '/../../DbTestCase.php';

/**
 * @testCase
 */
final class GroupRepositoryTest extends DbTestCase
{

	public function testReadOne(): void
	{
		/** @var Models\Groups\GroupRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Groups\GroupRepository::class);

		$findQuery = new Queries\FindGroupsQuery();
		$findQuery->byId(Uuid\Uuid::fromString('89f4a14f-7f78-4216-99b8-584ab9229f1c'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Groups\Group::class, $entity);
		Assert::same('Sleeping room', $entity->getName());
	}

	public function testReadResultSet(): void
	{
		/** @var Models\Groups\GroupRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Groups\GroupRepository::class);

		$findQuery = new Queries\FindGroupsQuery();

		$resultSet = $repository->getResultSet($findQuery);

		Assert::type(DoctrineOrmQuery\ResultSet::class, $resultSet);
		Assert::same(3, $resultSet->getTotalCount());
	}

}

$test_case = new GroupRepositoryTest();
$test_case->run();
