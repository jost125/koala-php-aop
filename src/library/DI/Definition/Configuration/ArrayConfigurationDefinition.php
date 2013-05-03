<?php

namespace DI\Definition\Configuration;

use DI\Definition\Configuration\ConfigurationDefinition;
use DI\Definition\Configuration\ServiceDefinition;
use DI\Definition\Configuration\ArrayServiceDefinition;

class ArrayConfigurationDefinition implements ConfigurationDefinition {

	public function __construct(array $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * @param string $serviceId
	 * @return boolean
	 */
	public function serviceExists($serviceId) {
		return array_key_exists($serviceId, $this->configuration['services']);
	}

	/**
	 * @param string $serviceId
	 * @return ServiceDefinition
	 */
	public function getServiceDefinition($serviceId) {
		return new ArrayServiceDefinition($this->configuration['services'][$serviceId]);
	}

	/**
	 * @return ServiceDefinition[]
	 */
	public function getServiceDefinitions() {
		$serviceDefinitions = array();
		foreach ($this->configuration['services'] as $serviceId => $foo) {
			$serviceDefinitions[$serviceId] = $this->getServiceDefinition($serviceId);
		}

		return $serviceDefinitions;
	}

	/**
	 * @param string $serviceId
	 * @param ServiceDefinition $proxyServiceDefinition
	 * @return void
	 */
	public function replaceServiceDefinition($serviceId, ServiceDefinition $proxyServiceDefinition) {
		$this->configuration['services'][$serviceId] = array(
			'serviceId' => $serviceId,
			'class' => $proxyServiceDefinition->getClassName(),
			'arguments' => $proxyServiceDefinition->getConstructorArguments()
		);
	}
}
