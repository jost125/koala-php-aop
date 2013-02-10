<?php

namespace AOP\AspectServiceFilter;

use ReflectionClass;

class PhpNativeAspectServiceFilter implements \AOP\AspectServiceFilter {

	private $annotationResolver;

	public function __construct(\Reflection\AnnotationResolver $annotationResolver) {
		$this->annotationResolver = $annotationResolver;
	}

	/**
	 * @param \DI\Definition\ServiceDefinition[] $serviceDefinitions
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions) {
		$aspectDefinitions = array();
		foreach ($serviceDefinitions as $serviceDefinition) {
			if ($this->isAspect($serviceDefinition)) {
				$aspectDefinitions[$serviceDefinition->getServiceId()] = $serviceDefinition;
			}
		}

		return $aspectDefinitions;
	}

	private function isAspect(\DI\Definition\ServiceDefinition $serviceDefinition) {
		return $this->annotationResolver->hasClassAnnotation(new ReflectionClass($serviceDefinition->getClassName()), new \Reflection\AnnotationExpression('\AOP\Aspect'));
	}
}
