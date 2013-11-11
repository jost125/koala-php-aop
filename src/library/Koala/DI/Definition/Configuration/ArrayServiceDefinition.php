<?php

namespace DI\Definition\Configuration;

use DI\Definition\Argument\ParameterArgument;
use DI\Definition\Argument\ServiceDependency;
use DI\Definition\Argument\SetupMethod;
use DI\Definition\Argument\ValueArgument;
use DI\Definition\Argument\WiringArgument;
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
	 * 		array('service' => 'serviceId'),
	 * 	),
	 * 	'setup' => array(
	 * 		'methodName' => array(
	 *				array('service' => 'serviceId'),
	 *				array('param' => 'paramValue'),
	 * 		),
	 * 		'otherMethodName' => array(
	 *				array('service' => 'serviceId'),
	 *				array('param' => 'paramValue'),
	 * 		),
	 * 	),
	 * )
	 */
	public function __construct(array $serviceDefinition) {
		$this->serviceDefinition = $serviceDefinition;
	}

	/**
	 * @return WiringArgument[]
	 */
	public function getConstructorArguments() {
		return $this->getArguments($this->serviceDefinition['arguments']);
	}

	/**
	 * @return SetupMethod[]
	 */
	public function getSetupMethods() {
		$methods = [];
		$setup = isset($this->serviceDefinition['setup']) ? $this->serviceDefinition['setup'] : [];
		foreach ($setup as $methodName => $methodArguments) {
			$methods[] = $methodArguments instanceof SetupMethod ? $methodArguments : new SetupMethod($methodName, $this->getArguments($methodArguments));
		}

		return $methods;
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

	public function getArguments($methodArguments) {
		$arguments = array();
		foreach ($methodArguments as $argumentEntry) {
			if (is_array($argumentEntry)) {
				$argumentType = key($argumentEntry);
				$argumentValue = $argumentEntry[$argumentType];
				switch ($argumentType) {
					case 'service':
						$arguments[] = new ServiceDependency($argumentValue);
						break;
					case 'param':
						$arguments[] = new ParameterArgument($argumentValue);
						break;
					case 'value':
						$arguments[] = new ValueArgument($argumentValue);
						break;
					default:
						throw new InvalidArgumentException('Invalid argument ' . $argumentType);
				}
			} else {
				$arguments[] = $argumentEntry;
			}
		}

		return $arguments;
	}

	/**
	 * @return boolean
	 */
	public function hasSetupMethods() {
		return array_key_exists('setup', $this->serviceDefinition) && count($this->serviceDefinition['setup']);
	}
}
