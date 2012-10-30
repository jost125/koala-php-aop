<?php

namespace DI;

class Container {
	private $services = array();
	private $config = array(
		'services' => array(
			'articleController' => array(
				'class' => '\Example\Controller\ArticleController',
				'arguments' => array(
					'service' => 'articleModelFacade'
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
	);

	public function getService($serviceId) {
		if (!$this->isServiceCreated($serviceId)) {
			$this->createService($serviceId);
		}
		return $this->getServiceInstance($serviceId);
	}

	private function isServiceCreated($serviceId) {
		return array_key_exists($serviceId, $this->services);
	}

	private function createService($serviceId) {
		if (array_key_exists($serviceId, $this->config['services'])) {
			$serviceDefinition = $this->config['services'][$serviceId];
			$arguments = array();
			foreach ($serviceDefinition['arguments'] as $argumentType => $argumentValue) {
				switch ($argumentType) {
					case 'service':
						$arguments[] = $this->getService($argumentValue);
						break;
				}
			}

			$reflectionClass = new \ReflectionClass($serviceDefinition['class']);
			if (empty($arguments)) {
				$this->services[$serviceId] = $reflectionClass->newInstance();
			} else {
				$this->services[$serviceId] = $reflectionClass->newInstanceArgs($arguments);
			}
		} else {
			// TODO add this exception
			throw new ServiceNotExists();
		}
	}

	private function getServiceInstance($serviceId) {
		return $this->services[$serviceId];
	}
}
