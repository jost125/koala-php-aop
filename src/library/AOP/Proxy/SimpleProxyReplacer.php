<?php

namespace AOP\Proxy;

use AOP\Aspect\AspectServiceFilter;
use AOP\Proxy\ProxyBuilder;
use AOP\Proxy\ProxyReplacer;
use DI\Definition\ConfigurationDefinition;

class SimpleProxyReplacer implements ProxyReplacer {

	private $aspectReflectionResolver;
	private $proxyBuilder;

	function __construct(AspectServiceFilter $aspectReflectionResolver, ProxyBuilder $proxyBuilder) {
		$this->aspectReflectionResolver = $aspectReflectionResolver;
		$this->proxyBuilder = $proxyBuilder;
	}

	/**
	 * @param ConfigurationDefinition $configurationDefinition
	 * @return ConfigurationDefinition
	 */
	public function replaceProxies(ConfigurationDefinition $configurationDefinition) {
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

	private function replaceBuildProxies(ConfigurationDefinition $configurationDefinition, array $proxyDefinitions) {
		foreach ($proxyDefinitions as $serviceId => $proxyDefinition) {
			$configurationDefinition->replaceServiceDefinition($serviceId, $proxyDefinition);
		}

		return $configurationDefinition;
	}
}
