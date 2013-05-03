<?php

namespace AOP\Proxy;

use DI\Definition\ServiceDefinition;

interface ProxyBuilder {

	/**
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ServiceDefinition[]
	 */
	public function buildProxies(array $aspectDefinitions, array $targetDefinitions);
}
