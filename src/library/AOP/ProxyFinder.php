<?php

namespace AOP;

interface ProxyFinder {

	/**
	 * @param \DI\Definition\ServiceDefinition[] $aspectDefinitions
	 * @param \DI\Definition\ServiceDefinition[] $targetDefinitions
	 * @return \AOP\Abstraction\ProxyList
	 */
	public function findProxies(array $aspectDefinitions, array $targetDefinitions);
}
