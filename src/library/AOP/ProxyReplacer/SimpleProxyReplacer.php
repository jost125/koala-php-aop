<?php

namespace AOP\ProxyReplacer;

class SimpleProxyReplacer implements \AOP\ProxyReplacer {

	private $aspectReflectionResolver;
	private $proxyBuilder;

	function __construct(\AOP\AspectServiceFilter $aspectReflectionResolver, \AOP\ProxyBuilder $proxyBuilder) {
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

		$aspectDefinitions = $this->aspectReflectionResolver->filterAspectServices($serviceDefinitions);
		$targetDefinitions = $this->subtractAspectsFromServices($serviceDefinitions, $aspectDefinitions);
		$proxyDefinitions = $this->proxyBuilder->buildProxies($aspectDefinitions, $targetDefinitions);

		return $this->replaceBuildProxies($configurationDefinition, $proxyDefinitions);
	}

	private function subtractAspectsFromServices(array $serviceDefinitions, array $aspectDefinitions) {
		$notAspects = $serviceDefinitions;
		foreach ($aspectDefinitions as $serviceId => $foo) {
			unset($notAspects[$serviceId]);
		}

		return $notAspects;
	}

	private function replaceBuildProxies(\DI\Definition\ConfigurationDefinition $configurationDefinition, array $proxyDefinitions) {
		foreach ($proxyDefinitions as $serviceId => $proxyDefinition) {
			$configurationDefinition->replaceServiceDefinition($serviceId, $proxyDefinition);
		}

		return $configurationDefinition;
	}
}
