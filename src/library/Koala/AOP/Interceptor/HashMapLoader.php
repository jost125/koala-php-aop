<?php

namespace Koala\AOP\Interceptor;

use Koala\DI\IContainer;
use ReflectionMethod;

class HashMapLoader implements Loader {

	private $interceptors;
	private $loadedInterceptors;
	private $container;

	public function __construct(IContainer $container, array $interceptors = []) {
		$this->interceptors = $interceptors;
		$this->container = $container;
		$this->loadedInterceptors = [];
	}

	public function loadInterceptors(ReflectionMethod $reflectionMethod) {
		$key = $this->getKey($reflectionMethod);
		if (!isset($this->interceptors[$key])) {
			return [];
		}

		if (isset($this->loadedInterceptors[$key])) {
			return $this->loadedInterceptors[$key];
		}

		$this->loadedInterceptors[$key] = $this->load($key);

		return new MethodInterceptorList(
			isset($this->loadedInterceptors[$key][InterceptorTypes::BEFORE]) ? $this->loadedInterceptors[$key][InterceptorTypes::BEFORE] : [],
			isset($this->loadedInterceptors[$key][InterceptorTypes::AROUND]) ? $this->loadedInterceptors[$key][InterceptorTypes::AROUND] : [],
			isset($this->loadedInterceptors[$key][InterceptorTypes::AFTER]) ? $this->loadedInterceptors[$key][InterceptorTypes::AFTER] : [],
			isset($this->loadedInterceptors[$key][InterceptorTypes::AFTER_RETURNING]) ? $this->loadedInterceptors[$key][InterceptorTypes::AFTER_RETURNING] : [],
			isset($this->loadedInterceptors[$key][InterceptorTypes::AFTER_THROWING]) ? $this->loadedInterceptors[$key][InterceptorTypes::AFTER_THROWING] : []
		);
	}

	private function getKey(ReflectionMethod $reflectionMethod) {
		return $reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName();
	}

	private function load($key) {
		$interceptors = [];
		foreach ($this->interceptors[$key] as $type => $ids) {
			foreach ($ids as list($id, $adviceMethod)) {
				$interceptors[$type][] = new Interceptor($this->container->getService($id), $adviceMethod);
			}
		}
		return $interceptors;
	}
}
