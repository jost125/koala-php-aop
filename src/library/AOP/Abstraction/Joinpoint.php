<?php

namespace AOP\Abstraction;

use ReflectionMethod;

class Joinpoint {

	private $reflectionMethod;

	public function __construct(ReflectionMethod $reflectionMethod) {
		$this->reflectionMethod;
	}

	public function getReflectionMethod() {
		return $this->reflectionMethod;
	}

}
