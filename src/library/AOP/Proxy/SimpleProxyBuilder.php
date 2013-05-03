<?php

namespace AOP\Proxy;

use AOP\Proxy\ProxyBuilder;
use AOP\Proxy\ProxyFinder;
use AOP\Proxy\ProxyGenerator;
use DI\Definition\Configuration\ServiceDefinition;

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
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ServiceDefinition[] 	service definition of built proxies
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
