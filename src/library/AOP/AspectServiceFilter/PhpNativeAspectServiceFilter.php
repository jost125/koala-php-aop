<?php

namespace AOP\AspectServiceFilter;

use AOP\AspectServiceFilter;
use DI\Definition\ServiceDefinition;
use Reflection\AnnotationExpression;
use Reflection\AnnotationResolver;
use ReflectionClass;

class PhpNativeAspectServiceFilter implements AspectServiceFilter {

	private $annotationResolver;

	public function __construct(AnnotationResolver $annotationResolver) {
		$this->annotationResolver = $annotationResolver;
	}

	/**
	 * @param ServiceDefinition[] $serviceDefinitions
	 * @return ServiceDefinition[]
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

	private function isAspect(ServiceDefinition $serviceDefinition) {
		return $this->annotationResolver->hasClassAnnotation(new ReflectionClass($serviceDefinition->getClassName()), new AnnotationExpression('\AOP\Aspect'));
	}
}
