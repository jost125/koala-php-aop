<?php

use Example\Aspect\ExceptionLogger;
use Example\Aspect\JsonResponseConvertor;
use Example\Aspect\NameWrapper;
use Example\Aspect\PrivateAspect;
use Example\Aspect\TemplateResponseConvertor;
use Example\Aspect\TestingAspect;
use Example\Controller\HiController;
use Example\DAO\UserDAO;
use Koala\DI\Definition\Configuration\ArrayConfigurationDefinition;

return new ArrayConfigurationDefinition(
	[
		'params' => [
			'my.param' => 'hi',
		],
		'services' => [
			'hiController' => [
				'serviceId' => 'hiController',
				'class' => HiController::class,
				'arguments' => [
					['service' => 'userDAO'],
				],
				'setup' => [
					'setHiMessage' => [
						['param' => 'my.param'],
					]
				],
			],
			'userDAO' => [
				'serviceId' => 'userDAO',
				'class' => UserDAO::class,
				'arguments' => [],
			],
//			'logger' => [
//				'serviceId' => 'logger',
//				'class' => '\Example\Logger\StdLogger',
//				'arguments' => [],
//			],
//			'executionLogging' => [
//				'serviceId' => 'executionLogging',
//				'class' => '\Example\Aspect\ExecutionLogging',
//				'arguments' => [
//					['service' => 'logger'],
//				],
//			],
//			'nameWrapper' => [
//				'serviceId' => 'nameWrapper',
//				'class' => NameWrapper::class,
//				'arguments' => [],
//			],
//			'testingAspect' => [
//				'serviceId' => 'testingAspect',
//				'class' => TestingAspect::class,
//				'arguments' => [],
//			],
			'jsonResponseConvertor' => [
				'serviceId' => 'jsonResponseConvertor',
				'class' => JsonResponseConvertor::class,
				'arguments' => [],
			],
//			'templateResponseConvertor' => [
//				'serviceId' => 'templateResponseConvertor',
//				'class' => TemplateResponseConvertor::class,
//				'arguments' => [
//					['value' => __DIR__ . '/views'],
//				],
//			],
//			'exceptionLogger' => [
//				'serviceId' => 'exceptionLogger',
//				'class' => ExceptionLogger::class,
//				'arguments' => [
//					['service' => 'logger']
//				],
//			],
		]
	]
);
