<?php

namespace AOP;

interface AspectServiceFilter {

	/**
	 * @param \DI\Definition\ServiceDefinition[] $serviceDefinitions
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions);
}
