<?php

namespace AOP\Interceptor;

use InvalidArgumentException;
use ReflectionMethod;

class HashMapLoader implements Loader {

	private $interceptors;

	public function __construct(array $interceptors) {
		$this->interceptors = $interceptors;
	}

	public function loadInterceptors(ReflectionMethod $reflectionMethod) {
		$key = $this->getKey($reflectionMethod);
		if (array_key_exists($key, $this->interceptors)) {
			return $this->interceptors[$key];
		}

		throw new InvalidArgumentException('No interceptors found');
	}

	private function getKey(ReflectionMethod $reflectionMethod) {
		return $reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName();
	}
}
