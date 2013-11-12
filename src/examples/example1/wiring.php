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
