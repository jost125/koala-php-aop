<?php

namespace DI\Definition\Configuration;

use DI\Definition\Argument\ParameterArgument;
use DI\Definition\Argument\WiringArgument;
use DI\Definition\Argument\ServiceDependency;
use DI\Definition\Configuration\ServiceDefinition;
use InvalidArgumentException;
use ReflectionClass;

class ArrayServiceDefinition implements ServiceDefinition {

	private $serviceDefinition;

	/**
	 * @param array $serviceDefinition
	 * array(
	 * 	'serviceId' => 'serviceId',
	 * 	'class' => '\Namespace\ClassName',
	 * 	'arguments' => array(
	 * 			array('service' => 'serviceId'),
	 * 	)
	 * )
	 */
	public function __construct(array $serviceDefinition) {
		$this->checkDefinition($serviceDefinition);
		$this->serviceDefinition = $serviceDefinition;
	}

	/**
	 * @return WiringArgument[]
	 */
	public function getConstructorArguments() {
		$arguments = array();
		foreach ($this->serviceDefinition['arguments'] as $argumentEntry) {
			$argumentType = key($argumentEntry);
			$argumentValue = $argumentEntry[$argumentType];
			switch ($argumentType) {
				case 'service':
					$arguments[] = new ServiceDependency($argumentValue);
					break;
				case 'param':
					$arguments[] = new ParameterArgument($argumentValue);
			}
		}

		return $arguments;
	}

	/**
	 * @return ReflectionClass
	 */
	public function getClassReflection() {
		return new ReflectionClass($this->serviceDefinition['class']);
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

	/**
	 * @return string
	 */
	public function getServiceId() {
		return $this->serviceDefinition['serviceId'];
	}

	private function checkDefinition($serviceDefinition) {
		if (!array_key_exists('serviceId', $serviceDefinition) || !array_key_exists('class', $serviceDefinition)) {
			throw new InvalidArgumentException('Provide serviceId and class');
		}

		if (array_key_exists('arguments', $serviceDefinition)) {
			foreach ($serviceDefinition['arguments'] as $argument) {
				switch (key($argument)) {
					case 'service':
						break;
					case 'param':
						break;
					default:
						throw new InvalidArgumentException('Invalid argument ' . $argument);
				}
			}
		}
	}
}
