<?php

namespace AOP;

interface ProxyBuilder {

	/**
	 * @param \DI\Definition\ServiceDefinition[] $aspectDefinitions
	 * @param \DI\Definition\ServiceDefinition[] $targetDefinitions
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function buildProxies(array $aspectDefinitions, array $targetDefinitions);
}
