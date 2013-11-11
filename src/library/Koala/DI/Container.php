<?php

namespace Koala\DI;

use Koala\AOP\Proxy\ProxyReplacer;
use Koala\DI\Definition\Argument\WiringArgument;
use Koala\DI\Definition\Configuration\ConfigurationDefinition;
use Koala\DI\Definition\Configuration\ServiceDefinition;
use Koala\DI\ServiceNotExistsException;

class Container implements IContainer {
	private $services = array();
	private $configurationDefinition;
	private $proxyReplacer;

	public function __construct(ConfigurationDefinition $configurationDefinition, ProxyReplacer $proxyReplacer) {
		$this->proxyReplacer = $proxyReplacer;
		$this->configurationDefinition = $proxyReplacer->replaceProxies($configurationDefinition);
		$this->services['container'] = $this;
	}

	public function getService($serviceId) {
		if (!$this->isServiceCreated($serviceId)) {
			$this->createService($serviceId);
		}
		return $this->getServiceInstance($serviceId);
	}

	public function getParameter($parameterId) {
		if (!$this->configurationDefinition->hasParameter($parameterId)) {
			throw new ParameterNotExistsException();
		}
		return $this->configurationDefinition->getParameter($parameterId);
	}

	private function isServiceCreated($serviceId) {
		return isset($this->services[$serviceId]);
	}

	private function createService($serviceId) {
		if ($this->configurationDefinition->serviceExists($serviceId)) {
			$serviceDefinition = $this->configurationDefinition->getServiceDefinition($serviceId);
			$instance = $this->initService($serviceDefinition);
			$this->setupService($serviceDefinition, $instance);

			$this->services[$serviceId] = $instance;
		} else {
			throw new ServiceNotExistsException();
		}
	}

	private function getServiceInstance($serviceId) {
		return $this->services[$serviceId];
	}

	private function initService(ServiceDefinition $serviceDefinition) {
		$reflectionClass = $serviceDefinition->getClassReflection();
		if ($serviceDefinition->hasConstructorArguments()) {
			$constructorArgumentValues = $this->getArgumentValues($serviceDefinition->getConstructorArguments());
			$instance = $reflectionClass->newInstanceArgs($constructorArgumentValues);
			return $instance;
		} else {
			$instance = $reflectionClass->newInstance();
			return $instance;
		}
	}

	private function setupService(ServiceDefinition $serviceDefinition, $instance) {
		if ($serviceDefinition->hasSetupMethods()) {
			foreach($serviceDefinition->getSetupMethods() as $setupMethod) {
				$serviceDefinition->getClassReflection()->getMethod($setupMethod->getMethodName())->invokeArgs($instance, $this->getArgumentValues($setupMethod->getArguments()));
			}
		}
	}

	/**
	 * @param WiringArgument[] $arguments
	 * @return array
	 */
	private function getArgumentValues(array $arguments) {
		$argumentValues = array();
		foreach ($arguments as $argument) {
			$argumentValues[] = $argument->getValue($this);
		}
		return $argumentValues;
	}
}
