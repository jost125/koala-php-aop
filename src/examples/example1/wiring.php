<?php

use Koala\DI\Definition\Configuration\ArrayConfigurationDefinition;

return new ArrayConfigurationDefinition(
	array(
		'params' => array(
			'my.param' => 'hi',
		),
		'services' => array(
			'hiController' => array(
				'serviceId' => 'hiController',
				'class' => '\Example\Controller\HiController',
				'arguments' => array(),
				'setup' => array(
					'setHiMessage' => array(
						array('param' => 'my.param')
					)
				),
			),
			'articleController' => array(
				'serviceId' => 'articleController',
				'class' => '\Example\Controller\ArticleController',
				'arguments' => array(
					array('param' => 'my.param'),
					array('service' => 'articleModelFacade'),
				),
			),
			'articleModelFacade' => array(
				'serviceId' => 'articleModelFacade',
				'class' => '\Example\Model\Facade\ArticleModelFacade',
				'arguments' => array(),
			),
			'logger' => array(
				'serviceId' => 'logger',
				'class' => '\Example\Logger\StdLogger',
				'arguments' => array(),
			),
			'executionLogging' => [
				'serviceId' => 'executionLogging',
				'class' => '\Example\Aspect\ExecutionLogging',
				'arguments' => [
					['service' => 'logger'],
				],
			],
		)
	)
);
