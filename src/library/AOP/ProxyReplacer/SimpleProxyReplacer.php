<?php

namespace AOP\ProxyReplacer;

class SimpleProxyReplacer implements \AOP\ProxyReplacer {

	private $aspectReflectionResolver;
	private $proxyBuilder;

	function __construct(\AOP\AspectReflectionResolver $aspectReflectionResolver, \AOP\ProxyBuilder $proxyBuilder) {
		$this->aspectReflectionResolver = $aspectReflectionResolver;
		$this->proxyBuilder = $proxyBuilder;
	}

	/**
	 * @param \DI\Definition\ConfigurationDefinition $configurationDefinition
	 * @return \DI\Definition\ConfigurationDefinition
	 */
	public function replaceProxies(\DI\Definition\ConfigurationDefinition $configurationDefinition) {
		$serviceDefinitions = $configurationDefinition->getServiceDefinitions();
		if (empty($serviceDefinitions)) {
			return $configurationDefinition;
		}

		$aspectServiceDefinitions = $this->aspectReflectionResolver->filterAspectServices($serviceDefinitions);
		$possibleTargetServiceDefinitions = $this->subtractAspectsFromServices($serviceDefinitions, $aspectServiceDefinitions);
		$proxyServiceDefinitions = $this->proxyBuilder->buildProxies($aspectServiceDefinitions, $possibleTargetServiceDefinitions);

		return $this->replaceBuildProxies($configurationDefinition, $proxyServiceDefinitions);
	}

	private function subtractAspectsFromServices(array $serviceDefinitions, array $aspectServiceDefinitions) {
		$notAspects = $serviceDefinitions;
		foreach ($aspectServiceDefinitions as $serviceId => $foo) {
			unset($notAspects[$serviceId]);
		}

		return $notAspects;
	}

	private function replaceBuildProxies(\DI\Definition\ConfigurationDefinition $configurationDefinition, array $proxyServiceDefinitions) {
		foreach ($proxyServiceDefinitions as $serviceId => $proxyServiceDefinition) {
			$configurationDefinition->replaceServiceDefinition($serviceId, $proxyServiceDefinition);
		}

		return $configurationDefinition;
	}
}
