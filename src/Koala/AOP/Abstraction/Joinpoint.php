<?php

namespace Koala\AOP\Abstraction;

use ReflectionMethod;

class Joinpoint {

	private $reflectionMethod;

	public function __construct(ReflectionMethod $reflectionMethod) {
		$this->reflectionMethod = $reflectionMethod;
	}

	public function getReflectionMethod() {
		return $this->reflectionMethod;
	}

}
