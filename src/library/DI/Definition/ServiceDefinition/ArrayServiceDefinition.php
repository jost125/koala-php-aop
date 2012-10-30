<?php

namespace DI\Definition\ServiceDefinition;

class ArrayServiceDefinition implements \DI\Definition\ServiceDefinition {

	private $serviceDefinition;

	public function __construct(array $serviceDefinition) {
		$this->serviceDefinition = $serviceDefinition;
	}

	/**
	 * @return \DI\Definition\ConstructorArgument[]
	 */
	public function getConstructorArguments() {
		$arguments = array();
		foreach ($this->serviceDefinition['arguments'] as $argumentEntry) {
			$argumentType = key($argumentEntry);
			$argumentValue = $argumentEntry[$argumentType];
			switch ($argumentType) {
				case 'service':
					$arguments[] = new \DI\Definition\ConstructorArgument\ServiceDependency($argumentValue);
					break;
			}
		}

		return $arguments;
	}

	/**
	 * @return \ReflectionClass
	 */
	public function getClassReflection() {
		return new \ReflectionClass($this->serviceDefinition['class']);
	}

	/**
	 * @return boolean
	 */
	public function hasConstructorArguments() {
		return array_key_exists('arguments', $this->serviceDefinition) && !empty($this->serviceDefinition['arguments']);
	}

	/**
	 * @return string
	 */
	public function getClassName() {
		return $this->serviceDefinition['class'];
	}
}
