<?php

namespace Koala\AOP;

use Koala\AOP\Interceptor\Interceptor;
use ReflectionMethod;

class Joinpoint {

	private $reflectionMethod;
	private $arguments;
	private $interceptedObject;
	private $aroundInterceptors;
	private $proceedIndex;

	public function __construct($interceptedObject, ReflectionMethod $reflectionMethod, array $arguments) {
		$this->reflectionMethod = $reflectionMethod;
		$this->arguments = $arguments;
		$this->interceptedObject = $interceptedObject;
		$this->aroundInterceptors = array();
		$this->proceedIndex = 0;
	}

	/**
	 * @return mixed
	 */
	public function proceed() {
		if (count($this->aroundInterceptors) == 0 || $this->proceedIndex == count($this->aroundInterceptors)) {
			return $this->reflectionMethod->invokeArgs($this->interceptedObject, $this->arguments);
		}
		else {
			/** @var Interceptor $interceptor */
			$interceptor = $this->aroundInterceptors[$this->proceedIndex++];
			return $interceptor->invoke($this);
		}
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}

	public function getArgument($index) {
		return $this->arguments[$index];
	}

	public function setArgument($value, $index) {
		return $this->arguments[$index] = $value;
	}

	public function getClassName() {
		return $this->reflectionMethod->getDeclaringClass()->getName();
	}

	public function getClassShortName() {
		return $this->reflectionMethod->getDeclaringClass()->getShortName();
	}

	public function getMethodName() {
		return $this->reflectionMethod->getName();
	}

	public function getReflectionMethod() {
		return $this->reflectionMethod;
	}

	public function setAroundInterceptors($aroundInterceptors) {
		$this->aroundInterceptors = $aroundInterceptors;
	}

}
