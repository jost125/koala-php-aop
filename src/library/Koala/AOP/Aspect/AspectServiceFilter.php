<?php

namespace Koala\AOP\Aspect;

use Koala\DI\Definition\Configuration\ServiceDefinition;

interface AspectServiceFilter {

	/**
	 * @param ServiceDefinition[] $serviceDefinitions
	 * @return ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions);
}
