<?php

namespace AOP;

interface ProxyBuilder {

	/**
	 * @param \DI\Definition\ServiceDefinition[] $aspectServiceDefinitions
	 * @param \DI\Definition\ServiceDefinition[] $possibleTargetServiceDefinitions
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function buildProxies(array $aspectServiceDefinitions, array $possibleTargetServiceDefinitions);
}
