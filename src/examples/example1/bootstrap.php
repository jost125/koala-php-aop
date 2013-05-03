<?php

spl_autoload_register(function($className) {
	if (preg_match('~^[\\a-zA-Z0-9]+$~', $className)) {
		$fileName = preg_replace('~\\\\~', '/', $className) . '.php';
		$filePath = __DIR__ . '/library/' . $fileName;
		if (file_exists($filePath)) {
			require_once $filePath;
		}
	}
});

require_once __DIR__ . '/../../library/loader.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$configurationDefinition = new \DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition(
	array(
		'services' => array(
			'articleController' => array(
				'serviceId' => 'articleController',
				'class' => '\Example\Controller\ArticleController',
				'arguments' => array(
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
		)
	)
);
$diContainer = new \DI\Container(
	$configurationDefinition,
	new \AOP\Proxy\NoProxyReplacer()
);
