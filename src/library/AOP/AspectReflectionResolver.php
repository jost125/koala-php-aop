<?php

namespace AOP;

interface AspectReflectionResolver {

	/**
	 * @param \DI\Definition\ServiceDefinition[] $serviceDefinitions
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions);
}
