<?php

namespace AOP\Aspect;

use DI\Definition\Configuration\ServiceDefinition;

interface AspectServiceFilter {

	/**
	 * @param ServiceDefinition[] $serviceDefinitions
	 * @return ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions);
}
