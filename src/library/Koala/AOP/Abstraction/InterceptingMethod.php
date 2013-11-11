<?php

namespace AOP\Abstraction;

use ReflectionMethod;

class InterceptingMethod {

	private $methodReflection;

	public function __construct(ReflectionMethod $reflectionMethod) {
		$this->methodReflection = $reflectionMethod;
	}

	public function getMethodReflection() {
		return $this->methodReflection;
	}

}
