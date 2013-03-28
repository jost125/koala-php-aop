<?php

namespace AOP\ProxyBuilder;

use AOP\ProxyBuilder;
use AOP\ProxyFinder;
use AOP\ProxyGenerator;

class SimpleProxyBuilder implements ProxyBuilder {

	private $proxyGenerator;
	private $proxyFinder;

	public function __construct(
		ProxyGenerator $proxyGenerator,
		ProxyFinder $proxyFinder
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
