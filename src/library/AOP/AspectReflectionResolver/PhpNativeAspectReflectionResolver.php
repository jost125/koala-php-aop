<?php

namespace AOP\AspectReflectionResolver;

class PhpNativeAspectReflectionResolver implements \AOP\AspectReflectionResolver {

	private $annotationResolver;

	public function __construct(\Reflection\AnnotationResolver $annotationResolver) {
		$this->annotationResolver = $annotationResolver;
	}

	/**
	 * @param \DI\Definition\ServiceDefinition[] $serviceDefinitions
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	public function filterAspectServices(array $serviceDefinitions) {
		$aspectServiceDefinitions = array();
		foreach ($serviceDefinitions as $serviceId => $serviceDefinition) {
			if ($this->isAspect($serviceDefinition)) {
				$aspectServiceDefinitions[$serviceId] = $serviceDefinition;
			}
		}

		return $aspectServiceDefinitions;
	}

	private function isAspect($serviceDefinition) {
		return $this->annotationResolver->hasClassAnnotation($serviceDefinition->getClassReflection(), new \Reflection\AnnotationExpression('\AOP\Aspect'));
	}
}
