<?php

namespace Koala\AOP\Interceptor;

use Koala\AOP\Joinpoint;
use ReflectionMethod;

class Interceptor {

	private $aspect;
	private $adviceReflectionMethod;

	public function __construct($aspect, ReflectionMethod $adviceReflectionMethod) {
		$this->aspect = $aspect;
		$this->adviceReflectionMethod = $adviceReflectionMethod;
	}

	public function invoke(Joinpoint $joinpoint) {
		return $this->adviceReflectionMethod->invokeArgs($this->aspect, array($joinpoint));
	}

}
