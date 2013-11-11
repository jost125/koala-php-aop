<?php

namespace AOP\Aspect;

use AOP\Aspect\AspectServiceFilter;
use DI\Definition\Configuration\ServiceDefinition;
use Reflection\Annotation\Parsing\AnnotationExpression;
use Reflection\Annotation\Parsing\AnnotationResolver;
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
