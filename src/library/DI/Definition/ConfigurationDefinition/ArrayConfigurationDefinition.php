<?php

namespace DI\Definition\ConfigurationDefinition;

class ArrayConfigurationDefinition implements \DI\Definition\ConfigurationDefinition {

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
	 * @return \DI\Definition\ServiceDefinition
	 */
	public function getServiceDefinition($serviceId) {
		return new \DI\Definition\ServiceDefinition\ArrayServiceDefinition($this->configuration['services'][$serviceId]);
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
	 * @param \DI\Definition\ServiceDefinition $proxyServiceDefinition
	 * @return void
	 */
	public function replaceServiceDefinition($serviceId, \DI\Definition\ServiceDefinition $proxyServiceDefinition) {
		$this->configuration['services'][$serviceId] = array(
			'class' => $proxyServiceDefinition->getClassName(),
			'arguments' => $proxyServiceDefinition->getConstructorArguments()
		);
	}
}
