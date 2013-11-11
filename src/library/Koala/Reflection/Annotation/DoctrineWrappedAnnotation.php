<?php

namespace Koala\Reflection\Annotation;

use ReflectionClass;
use ReflectionProperty;
use Koala\Reflection\Annotation\Annotation;

class DoctrineWrappedAnnotation implements Annotation {

	private $annotation;

	public function __construct(\Doctrine\Common\Annotations\Annotation $annotation) {
		$this->annotation = $this->transformToSimpleAnnotation($annotation);
	}

	public function getName() {
		return $this->annotation->getName();
	}

	public function hasParameters() {
		return $this->annotation->hasParameters();
	}

	public function getParameters() {
		return $this->annotation->getParameters();
	}

	public function toExpression() {
		return $this->annotation->toExpression();
	}

	public function getParameter($name) {
		return $this->annotation->getParameter($name);
	}

	private function transformToSimpleAnnotation(\Doctrine\Common\Annotations\Annotation $annotation) {
		$name = $this->getClassReflection($annotation)->getName();
		$parameters = array();
		foreach ($this->getClassProperites($annotation) as $property) {
			$value = $property->getValue($annotation);
			if ($value != null) {
				$parameters[$property->getName()] = $value;
			}
		}

		return new SimpleAnnotation($name, $parameters);
	}

	/**
	 * @param \Doctrine\Common\Annotations\Annotation $annotation
	 * @return ReflectionProperty[]
	 */
	private function getClassProperites(\Doctrine\Common\Annotations\Annotation $annotation) {
		$properties = array();
		foreach ($this->getClassReflection($annotation)->getProperties() as $property) {
			if ($property->getName() != 'class') {
				$properties[] = $property;
			}
		}

		return $properties;
	}

	private function getClassReflection(\Doctrine\Common\Annotations\Annotation $annotation) {
		return new ReflectionClass($annotation);
	}
}
