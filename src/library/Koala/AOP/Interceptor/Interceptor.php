<?php

namespace AOP\Interceptor;

use AOP\Joinpoint;
use ReflectionMethod;

class Interceptor {

	private $aspect;
	private $adviceReflectionMethod;

	public function __construct($aspect, ReflectionMethod $adviceReflectionMethod) {
		$this->aspect = $aspect;
		$this->adviceReflectionMethod = $adviceReflectionMethod;
	}

	public function invoke(Joinpoint $joinpoint) {
		$this->adviceReflectionMethod->invokeArgs($this->aspect, array($joinpoint));
	}

}
