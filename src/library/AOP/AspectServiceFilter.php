<?php

namespace AOP;

use DI\Definition\ServiceDefinition;

interface AspectServiceFilter {

	/**
	 * @param ServiceDefinition[] $serviceDefinitions
	 * @return ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions);
}
