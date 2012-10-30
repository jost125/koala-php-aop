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

$diContainer = new \DI\Container(
	new \DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition(
		array(
			'services' => array(
				'articleController' => array(
					'class' => '\Example\Controller\ArticleController',
					'arguments' => array(
						array('service' => 'articleModelFacade'),
					),
				),
				'articleModelFacade' => array(
					'class' => '\Example\Model\Facade\ArticleModelFacade',
					'arguments' => array(),
				),
				'logger' => array(
					'class' => '\Example\Logger\StdLogger',
					'arguments' => array(),
				),
				'stopwatchFactory' => array(
					'class' => '\Example\Stopwatch\StopwatchFactory',
					'arguments' => array(),
				)
			)
		)
	),
	new \AOP\ProxyReplacer\NoProxyReplacer()
);
