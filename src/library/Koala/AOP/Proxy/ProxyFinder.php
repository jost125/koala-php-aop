<?php

namespace Koala\AOP\Proxy;

use Koala\AOP\Abstraction\ProxyList;
use Koala\DI\Definition\Configuration\ServiceDefinition;

interface ProxyFinder {

	/**
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ProxyList
	 */
	public function findProxies(array $aspectDefinitions, array $targetDefinitions);
}
