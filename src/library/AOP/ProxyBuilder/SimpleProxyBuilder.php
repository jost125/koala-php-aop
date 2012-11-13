<?php

namespace AOP\ProxyBuilder;

class SimpleProxyBuilder implements \AOP\ProxyBuilder {

	private $proxyGenerator;
	private $proxyFinder;

	public function __construct(
		\AOP\ProxyGenerator $proxyGenerator,
		\AOP\ProxyFinder $proxyFinder
	) {
		$this->proxyGenerator = $proxyGenerator;
		$this->proxyFinder = $proxyFinder;
	}

	/**
	 * @param \DI\Definition\ServiceDefinition[] $aspectDefinitions
	 * @param \DI\Definition\ServiceDefinition[] $targetDefinitions
	 * @return \DI\Definition\ServiceDefinition[] 	service definition of built proxies
	 */
	public function buildProxies(array $aspectDefinitions, array $targetDefinitions) {
		if (empty($aspectDefinitions) || empty($targetDefinitions)) {
			return array();
		}

		$proxyList = $this->proxyFinder->findProxies($aspectDefinitions, $targetDefinitions);
		$proxyReplacements = $this->proxyGenerator->generateProxies($proxyList);

		return $proxyReplacements;
	}

}
