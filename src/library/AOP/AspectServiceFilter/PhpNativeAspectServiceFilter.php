<?php

namespace AOP\AspectServiceFilter;

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
		foreach ($serviceDefinitions as $serviceId => $serviceDefinition) {
			if ($this->isAspect($serviceDefinition)) {
				$aspectDefinitions[$serviceId] = $serviceDefinition;
			}
		}

		return $aspectDefinitions;
	}

	private function isAspect($serviceDefinition) {
		return $this->annotationResolver->hasClassAnnotation($serviceDefinition->getClassReflection(), new \Reflection\AnnotationExpression('\AOP\Aspect'));
	}
}
