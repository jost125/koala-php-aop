<?php

namespace AOP\Interceptor;

use AOP\Joinpoint;
use ReflectionMethod;

class MethodInvocation {

	private $proxyObject;
	private $targetMethod;
	private $interceptors;
	private $arguments;

	public function __construct($proxyObject, ReflectionMethod $targetMethod, array $arguments, MethodInterceptorList $interceptors) {
		$this->arguments = $arguments;
		$this->targetMethod = $targetMethod;
		$this->interceptors = $interceptors;
		$this->proxyObject = $proxyObject;
	}

	public function proceed() {
		$this->targetMethod->setAccessible(true);
		$joinpoint = new Joinpoint($this->proxyObject, $this->targetMethod, $this->arguments);
		$this->interceptors->interceptBefore($joinpoint);
		$result = $this->interceptors->interceptAround($joinpoint);
		$this->interceptors->interceptAfter($joinpoint, $result);

		return $result;
	}

}
