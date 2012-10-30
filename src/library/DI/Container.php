<?php

namespace DI;

class Container {
	private $services = array();
	private $configurationDefinition;
	private $proxyReplacer;

	public function __construct(\DI\Definition\ConfigurationDefinition $configurationDefinition, \AOP\ProxyReplacer $proxyReplacer) {
		$this->proxyReplacer = $proxyReplacer;
		$this->configurationDefinition = $proxyReplacer->replaceProxies($configurationDefinition);
	}

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
		if ($this->configurationDefinition->serviceExists($serviceId)) {
			$serviceDefinition = $this->configurationDefinition->getServiceDefinition($serviceId);
			$reflectionClass = $serviceDefinition->getClassReflection();

			if ($serviceDefinition->hasConstructorArguments()) {
				$constructorArgumentValues = $this->getConstructorArgumentValues($serviceDefinition);
				$this->services[$serviceId] = $reflectionClass->newInstanceArgs($constructorArgumentValues);
			} else {
				$this->services[$serviceId] = $reflectionClass->newInstance();
			}
		} else {
			throw new \DI\Exception\ServiceNotExistsException();
		}
	}

	private function getConstructorArgumentValues(\DI\Definition\ServiceDefinition $serviceDefinition) {
		$constructorArgumentValues = array();
		foreach ($serviceDefinition->getConstructorArguments() as $constructorArgument) {
			$constructorArgumentValues[] = $constructorArgument->getValue($this);
		}
		return $constructorArgumentValues;
	}

	private function getServiceInstance($serviceId) {
		return $this->services[$serviceId];
	}
}