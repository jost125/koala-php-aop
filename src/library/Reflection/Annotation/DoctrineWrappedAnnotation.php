<?php

namespace Reflection\Annotation;

use ReflectionClass;
use ReflectionProperty;
use Reflection\Annotation;

class DoctrineWrappedAnnotation implements Annotation {

	private $annotation;

	public function __construct(\Doctrine\Common\Annotations\Annotation $annotation) {
		$this->annotation = $annotation;
	}

	public function getName() {
		return $this->getClassReflection()->getName();
	}

	public function hasParameters() {
		return (bool)count($this->getParameters());
	}

	public function getParameters() {
		$parameters = array();
		foreach ($this->getClassProperites() as $property) {
			$value = $property->getValue($this->annotation);
			if ($value != null) {
				$parameters[$property->getName()] = $value;
			}
		}
		return $parameters;
	}

	public function toExpression() {
		$namePart = $this->getName();
		$valuesParts = array();
		foreach ($this->getParameters() as $parameterName => $parameterValue) {
			$valuesParts[] = $parameterName . '="' . str_replace('"', '\"', $parameterValue) . '"';
		}

		return $namePart . '(' . implode(', ', $valuesParts) . ')';
	}

	private function getClassReflection() {
		return new ReflectionClass($this->annotation);
	}

	/**
	 * @return ReflectionProperty[]
	 */
	private function getClassProperites() {
		$properties = array();
		foreach ($this->getClassReflection()->getProperties() as $property) {
			if ($property->getName() != 'class') {
				$properties[] = $property;
			}
		}

		return $properties;
	}
}
