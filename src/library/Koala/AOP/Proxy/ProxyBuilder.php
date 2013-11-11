<?php

namespace Koala\AOP\Proxy;

use Koala\DI\Definition\Configuration\ServiceDefinition;

interface ProxyBuilder {

	/**
	 * @param ServiceDefinition[] $aspectDefinitions
	 * @param ServiceDefinition[] $targetDefinitions
	 * @return ServiceDefinition[]
	 */
	public function buildProxies(array $aspectDefinitions, array $targetDefinitions);
}
