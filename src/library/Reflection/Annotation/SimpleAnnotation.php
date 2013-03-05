<?php

namespace Reflection\Annotation;

use Reflection\Annotation;

class SimpleAnnotation implements Annotation {

	private $name;
	private $parameters;

	function __construct($name, array $parameters) {
		$this->name = $name;
		$this->parameters = $parameters;
	}

	public function getName() {
		return $this->name;
	}

	public function hasParameters() {
		return (bool)count($this->parameters);
	}

	public function getParameters() {
		return $this->parameters;
	}

	public function toExpression() {
		$namePart = $this->getName();
		$valuesParts = array();
		foreach ($this->getParameters() as $parameterName => $parameterValue) {
			$valuesParts[] = $parameterName . '="' . str_replace('"', '\"', $parameterValue) . '"';
		}

		return $namePart . (count($valuesParts) ? '(' . implode(', ', $valuesParts) . ')' : '');
	}

	public function getParameter($name) {
		if (!array_key_exists($name, $this->parameters)) {
			throw new ParameterNotDefinedException('Property "' . $name . '" is not defined in ' . $this->getName() . ' annotation');
		}
		return $this->parameters[$name];
	}
}
