<?php

namespace Koala\AOP;

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
		if (empty($this->aroundInterceptors) || $this->proceedIndex >= count($this->aroundInterceptors)) {
			return $this->reflectionMethod->invokeArgs($this->interceptedObject, $this->arguments);
		}
		else {
			$interceptor = $this->aroundInterceptors[$this->proceedIndex++];
			$interceptor->invoke($this);
			return $this->proceed();
		}
	}

	/**
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}

	public function getClassName() {
		return $this->reflectionMethod->getDeclaringClass()->getNamespaceName();
	}

	public function getMethodName() {
		return $this->reflectionMethod->getName();
	}

	public function setAroundInterceptors($aroundInterceptors) {
		$this->aroundInterceptors = $aroundInterceptors;
	}

}
