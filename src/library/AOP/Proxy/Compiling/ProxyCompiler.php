<?php

namespace AOP\Proxy\Compiling;

use ReflectionClass;
use ReflectionMethod;

class ProxyCompiler {

	private $proxyNamespacePrefix;
	private $proxyMemberPrefix;

	public function __construct($proxyMemberPrefix, $proxyNamespacePrefix) {
		$this->proxyMemberPrefix = $proxyMemberPrefix;
		$this->proxyNamespacePrefix = $proxyNamespacePrefix;
	}

	/**
	 * @param ReflectionClass $targetClass
	 * @param ReflectionMethod[] $interceptedMethods
	 * @return CompiledProxy
	 */
	public function compileProxy(ReflectionClass $targetClass, array $interceptedMethods) {
		$proxyNamespace = $this->createProxyNamespace($targetClass);
		$proxyClassName = $this->createProxyClassName($targetClass);
		$fqnTargetClassName = $this->createNormalizedFQNTargetClassName($targetClass);
		$code =
			'<?php' . "\n\n" .
			'namespace ' . $proxyNamespace . ";\n\n" .
			'class ' . $proxyClassName . " extends " . $fqnTargetClassName . " {\n\n" .
			'	private $' . $this->proxyMemberPrefix . 'interceptorLoader' . ";\n\n" .
			$this->generateProxyMethods($interceptedMethods) .
			'	public function set' . $this->proxyMemberPrefix . 'interceptorLoader(\\AOP\\Interceptor\\Loader $loader) {' . "\n" .
			'		$this->' . $this->proxyMemberPrefix . 'interceptorLoader = $loader;' . "\n" .
			'	}' . "\n" .
			'}' . "\n";
		return new CompiledProxy($proxyNamespace . '\\' . $proxyClassName, $code);
	}

	private function createProxyClassName(ReflectionClass $targetClass) {
		return $targetClass->getShortName();
	}

	private function createNormalizedFQNTargetClassName(ReflectionClass $targetClass) {
		return '\\' . $targetClass->getName();
	}

	private function createProxyNamespace(ReflectionClass $targetClass) {
		if ($targetClass->getNamespaceName()) {
			return $this->proxyNamespacePrefix . '\\' . $targetClass->getNamespaceName();
		}
		return $this->proxyNamespacePrefix;
	}

	/**
	 * @param ReflectionMethod[] $interceptedMethods
	 * @return string
	 */
	private function generateProxyMethods(array $interceptedMethods) {
		$code = '';
		foreach ($interceptedMethods as $method) {
			$code .=
				'	public function ' . $method->getName() . '(' . $this->generateMethodArguments($method) . ') {' . "\n" .
					'		$reflectionMethod = new \\ReflectionMethod(\'' . $method->getDeclaringClass()->getName() . '\', \'' . $method->getName() . '\');' . "\n" .
					'		$interceptors = $this->' . $this->proxyMemberPrefix . 'interceptorLoader->loadInterceptors($reflectionMethod);' . "\n" .
					'		$invocation = new \\AOP\\Interceptor\\MethodInvocation($this, $reflectionMethod, func_get_args(), $interceptors);' . "\n" .
					'		return $invocation->proceed();' . "\n" .
					'	}' . "\n\n";
		}
		return $code;
	}

	private function generateMethodArguments(ReflectionMethod $reflectionMethod) {
		$parameters = $reflectionMethod->getParameters();
		$generated = array();
		foreach ($parameters as $parameter) {
			$name = $parameter->getName();
			$generated[] = $parameter->getClass() ? $parameter->getClass()->getNamespaceName() . $parameter->getClass()->getName() . ' $' . $name : '$' . $name;
		}

		return implode(', ', $generated);
	}

}
