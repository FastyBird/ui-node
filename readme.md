# FastyBird user interface node

[![Build Status](https://img.shields.io/travis/com/FastyBird/ui-node.svg?style=flat-square)](https://travis-ci.com/FastyBird/ui-node)
[![Code coverage](https://img.shields.io/coveralls/FastyBird/ui-node.svg?style=flat-square)](https://coveralls.io/r/FastyBird/ui-node)
![PHP](https://img.shields.io/packagist/php-v/fastybird/ui-node?style=flat-square)
[![Licence](https://img.shields.io/packagist/l/FastyBird/ui-node.svg?style=flat-square)](https://packagist.org/packages/FastyBird/ui-node)
[![Downloads total](https://img.shields.io/packagist/dt/FastyBird/ui-node.svg?style=flat-square)](https://packagist.org/packages/FastyBird/ui-node)
[![Latest stable](https://img.shields.io/packagist/v/FastyBird/ui-node.svg?style=flat-square)](https://packagist.org/packages/FastyBird/ui-node)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

## What is FastyBird user interface node?

User interface node is a microservice for managing user interface, creating visualisations and storing user interfaces presset for web and mobile app clients.

FastyBird user interface node is an Apache2 licensed distributed microservice, developed in PHP with [Nette framework](https://nette.org).

## Requirements

FastyBird user interface node is tested against PHP 7.4 and [ReactPHP http](https://github.com/reactphp/http) 0.8 event-driven, streaming plaintext HTTP server

## Getting started

> **NOTE:** If you don't want to install it manually, try [docker image](#install-with-docker)

The best way to install **fastybird/ui-node** is using [Composer](http://getcomposer.org/). If you don't have Composer yet, [download it](https://getcomposer.org/download/) following the instructions.
Then use command:

```sh
$ composer create-project --no-dev fastybird/ui-node path/to/install
$ cd path/to/install
```

Everything required will be then installed in the provided folder `path/to/install`

This microservice has one console command.

##### HTTP & WS server

```sh
$ vendor/bin/fb-console fb:web-server:start
```

This command is to start build in web server which is listening for incoming http api request messages from clients and is listening for new data from exchange bus from other microservices. 

## Install with docker

![Docker Image Version (latest by date)](https://img.shields.io/docker/v/fastybird/ui-node?style=flat-square)
![Docker Image Size (latest by date)](https://img.shields.io/docker/image-size/fastybird/ui-node?style=flat-square)
![Docker Cloud Build Status](https://img.shields.io/docker/cloud/build/fastybird/ui-node?style=flat-square)

Docker image: [fastybird/ui-node](https://hub.docker.com/r/fastybird/ui-node/)

### Use docker hub image

```bash
$ docker run -d -it --name devices fastybird/ui-node:latest
```

### Generate local image

```bash
$ docker build --tag=ui-node .
$ docker run -d -it --name ui-node ui-node
```

## Configuration

This microservices is preconfigured for default connections, but your infrastructure could be different.

Configuration could be made via environment variables:

| Environment Variable | Description |
| ---------------------- | ---------------------------- |
| `FB_APP_PARAMETER__EXCHANGE_HOST=127.0.0.1` | RabbitMQ host address |
| `FB_APP_PARAMETER__EXCHANGE_PORT=5672` | RabbitMQ access port |
| `FB_APP_PARAMETER__EXCHANGE_VHOST=/` | RabbitMQ vhost |
| `FB_APP_PARAMETER__EXCHANGE_USERNAME=guest` | Username |
| `FB_APP_PARAMETER__EXCHANGE_PASSWORD=guest` | Password |
| | |
| `FB_APP_PARAMETER__DATABASE_VERSION=5.7` | MySQL server version |
| `FB_APP_PARAMETER__DATABASE_HOST=127.0.0.1` | MySQL host address |
| `FB_APP_PARAMETER__DATABASE_PORT=3306` | MySQL access port |
| `FB_APP_PARAMETER__DATABASE_DBNAME=ui_node` | MySQL database name |
| `FB_APP_PARAMETER__DATABASE_USERNAME=root` | Username |
| `FB_APP_PARAMETER__DATABASE_PASSWORD=` | Password |
| | |
| `FB_APP_PARAMETER__WS_PORT=8080` | WS server access port |
| | |
| `FB_APP_PARAMETER__SERVER_ADDRESS=0.0.0.0` | HTTP server host address |
| `FB_APP_PARAMETER__SERVER_PORT=8000` | HTTP server access port |
| | |
| `FB_APP_PARAMETER__SECURITY_SIGNATURE=` | Security token signature string |

> **NOTE:** In case you are not using docker image or you are not able to configure environment variables, you could edit configuration file `./config/default.neon`

## Initialization

This microservice is using database, so database have to be initialise with basic database schema. It could be done via shell command:

```sh
$ php vendor/bin/doctrine orm:schema-tool:create
```

After this steps, microservice could be started with [server command](#http-server)

## Feedback

Use the [issue tracker](https://github.com/FastyBird/ui-node/issues) for bugs or [mail](mailto:code@fastybird.com) or [Tweet](https://twitter.com/fastybird) us for any idea that can improve the project.

Thank you for testing, reporting and contributing.

## Changelog

For release info check [release page](https://github.com/FastyBird/ui-node/releases)

## Maintainers

<table>
	<tbody>
		<tr>
			<td align="center">
				<a href="https://github.com/akadlec">
					<img width="80" height="80" src="https://avatars3.githubusercontent.com/u/1866672?s=460&amp;v=4">
				</a>
				<br>
				<a href="https://github.com/akadlec">Adam Kadlec</a>
			</td>
		</tr>
	</tbody>
</table>

***
Homepage [https://www.fastybird.com](https://www.fastybird.com) and repository [http://github.com/fastybird/ui-node](http://github.com/fastybird/ui-node).
