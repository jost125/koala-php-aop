<?php

namespace Koala\AOP\Interceptor;

use Exception;
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

	public function invokeWithResult(Joinpoint $joinpoint, $result) {
		$this->adviceReflectionMethod->invokeArgs($this->aspect, array($joinpoint, $result));
	}

	public function invokeWithException(Joinpoint $joinpoint, Exception $exception) {
		$this->adviceReflectionMethod->invokeArgs($this->aspect, array($joinpoint, $exception));
	}

}
