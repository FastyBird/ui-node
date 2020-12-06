<?php declare(strict_types = 1);

use FastyBird\UINode;

return [
	'create' => [
		[
			'id'         => '633c7f7c-f73b-456f-b65f-5359c3b23d9c',
			'device'     => 'device-name',
			'identifier' => 'device-name',
			'name'       => 'Device name',
			'title'      => null,
			'comment'    => null,
			'state'      => 'ready',
			'enabled'    => true,
			'control'    => ['reset', 'reboot'],
		],
		[
			'routing_key' => UINode\Constants::RABBIT_MQ_DEVICES_CREATED_ENTITY_ROUTING_KEY,
			'origin'      => UINode\Constants::NODE_DEVICES_ORIGIN,
			'data'        => [
				'id'         => '633c7f7c-f73b-456f-b65f-5359c3b23d9c',
				'device'     => 'device-name',
				'identifier' => 'device-name',
				'name'       => 'Device name',
				'title'      => null,
				'comment'    => null,
				'state'      => 'ready',
				'enabled'    => true,
				'control'    => ['reset', 'reboot'],
			],
		],
		UINode\Constants::RABBIT_MQ_DEVICES_CREATED_ENTITY_ROUTING_KEY,
		UINode\Constants::NODE_DEVICES_ORIGIN,
	],
	'update' => [
		[
			'id'         => '633c7f7c-f73b-456f-b65f-5359c3b23d9c',
			'device'     => 'device-name',
			'identifier' => 'device-name',
			'name'       => 'Device name',
			'title'      => null,
			'comment'    => null,
			'state'      => 'ready',
			'enabled'    => true,
			'control'    => ['reset', 'reboot'],
		],
		[
			'routing_key' => UINode\Constants::RABBIT_MQ_DEVICES_UPDATED_ENTITY_ROUTING_KEY,
			'origin'      => UINode\Constants::NODE_DEVICES_ORIGIN,
			'data'        => [
				'id'         => '633c7f7c-f73b-456f-b65f-5359c3b23d9c',
				'device'     => 'device-name',
				'identifier' => 'device-name',
				'name'       => 'Device name',
				'title'      => null,
				'comment'    => null,
				'state'      => 'ready',
				'enabled'    => true,
				'control'    => ['reset', 'reboot'],
			],
		],
		UINode\Constants::RABBIT_MQ_DEVICES_UPDATED_ENTITY_ROUTING_KEY,
		UINode\Constants::NODE_DEVICES_ORIGIN,
	],
	'delete' => [
		[
			'id'         => '633c7f7c-f73b-456f-b65f-5359c3b23d9c',
			'device'     => 'device-name',
			'identifier' => 'device-name',
			'name'       => 'Device name',
			'title'      => null,
			'comment'    => null,
			'state'      => 'ready',
			'enabled'    => true,
			'control'    => ['reset', 'reboot'],
		],
		[
			'routing_key' => UINode\Constants::RABBIT_MQ_DEVICES_DELETED_ENTITY_ROUTING_KEY,
			'origin'      => UINode\Constants::NODE_DEVICES_ORIGIN,
			'data'        => [
				'id'         => '633c7f7c-f73b-456f-b65f-5359c3b23d9c',
				'device'     => 'device-name',
				'identifier' => 'device-name',
				'name'       => 'Device name',
				'title'      => null,
				'comment'    => null,
				'state'      => 'ready',
				'enabled'    => true,
				'control'    => ['reset', 'reboot'],
			],
		],
		UINode\Constants::RABBIT_MQ_DEVICES_DELETED_ENTITY_ROUTING_KEY,
		UINode\Constants::NODE_DEVICES_ORIGIN,
	],
];
