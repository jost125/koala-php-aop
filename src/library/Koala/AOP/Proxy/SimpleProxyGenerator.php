<?php

namespace AOP\Proxy;

use AOP\Abstraction\Advice;
use AOP\Abstraction\Joinpoint;
use AOP\Abstraction\Pointcut\AfterPointcut;
use AOP\Abstraction\Pointcut\AroundPointcut;
use AOP\Abstraction\Pointcut\BeforePointcut;
use AOP\Abstraction\Proxy;
use AOP\Abstraction\ProxyList;
use AOP\Interceptor\HashMapLoader;
use AOP\Interceptor\InterceptorTypes;
use AOP\Proxy\Compiling\CompiledProxy;
use AOP\Proxy\Compiling\ProxyCompiler;
use DI\Definition\Configuration\ArrayServiceDefinition;
use DI\Definition\Configuration\ServiceDefinition;
use IO\Storage\FileStorage;
use ReflectionClass;

class SimpleProxyGenerator implements ProxyGenerator {

	private $proxyCompiler;
	private $proxyMemberPrefix;
	private $interceptorLoaderId;
	private $fileStorage;
	private $proxyDirectory;
	private $containerId;

	public function __construct(ProxyCompiler $proxyCompiler, $proxyMemberPrefix, $interceptorLoaderId, FileStorage $fileStorage, $proxyDirectory, $containerId) {
		$this->proxyCompiler = $proxyCompiler;
		$this->proxyMemberPrefix = $proxyMemberPrefix;
		$this->interceptorLoaderId = $interceptorLoaderId;
		$this->fileStorage = $fileStorage;
		$this->proxyDirectory = $proxyDirectory;
		$this->containerId = $containerId;
	}

	/**
	 * @param ProxyList $proxyAbstractionList
	 * @return ServiceDefinition[]
	 */
	public function generateProxies(ProxyList $proxyAbstractionList) {
		$proxyDefinitions = array();
		$interceptors = [];

		/** @var $proxy Proxy */
		foreach ($proxyAbstractionList as $proxy) {
			/** @var Joinpoint $joinpoint */
			$joinpointAdvices = $proxy->getJoinpointsAdvices();
			foreach ($joinpointAdvices->getKeys() as $joinpoint) {
				/** @var ServiceDefinition $aspectDefinition */
				/** @var Advice $advice */
				foreach ($joinpointAdvices->getValue($joinpoint) as list($advice, $aspectDefinition)) {
					$reflectionMethod = $joinpoint->getReflectionMethod();

					switch (get_class($advice->getPointcut())) {
						case BeforePointcut::class:
							$interceptorType = InterceptorTypes::BEFORE;
							break;
						case AfterPointcut::class:
							$interceptorType = InterceptorTypes::AFTER;
							break;
						case AroundPointcut::class:
							$interceptorType = InterceptorTypes::AROUND;
							break;
						default:
							throw new \InvalidArgumentException();
					}

					if (!isset($interceptors[$reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()][$interceptorType])) {
						$interceptors[$reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()][$interceptorType] = [];
					}

					$interceptors[$reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()][$interceptorType][] = [$aspectDefinition->getServiceId(), $advice->getInterceptingMethod()->getMethodReflection()];
				}
			}

			$compiledProxy = $this->compileProxy($proxy);
			$key = str_replace('\\', '_', $compiledProxy->getClassName());
			$this->fileStorage->put($key, $compiledProxy->getCode());
			include_once $this->proxyDirectory . '/' . $key . '.php';

			$arguments = $proxy->getTargetDefinition()->hasConstructorArguments() ? $proxy->getTargetDefinition()->getConstructorArguments() : array();

			$existingSetupMethods = $proxy->getTargetDefinition()->hasSetupMethods() ? $proxy->getTargetDefinition()->getSetupMethods() : array();
			foreach ($existingSetupMethods as $existingSetupMethod) {
				$setupMethods[$existingSetupMethod->getMethodName()] = $existingSetupMethod->getArguments();
			}
			$setupMethods['set' . $this->proxyMemberPrefix . 'interceptorLoader'] = array(
				array('service' => $this->interceptorLoaderId),
			);

			$definition = new ArrayServiceDefinition(array(
				'serviceId' => $proxy->getTargetDefinition()->getServiceId(),
				'class' => $compiledProxy->getClassName(),
				'arguments' => $arguments,
				'setup' => $setupMethods,
			));

			$proxyDefinitions[] = $definition;
		}

		$proxyDefinitions[] = new ArrayServiceDefinition([
			'serviceId' => $this->interceptorLoaderId,
			'class' => HashMapLoader::class,
			'arguments' => [
				['service' => $this->containerId],
				['value' => $interceptors],
			],
		]);

		return $proxyDefinitions;
	}

	/**
	 * @param Proxy $proxy
	 * @return CompiledProxy
	 */
	public function compileProxy(Proxy $proxy) {
		$targetClass = new ReflectionClass($proxy->getTargetDefinition()->getClassName());
		$interceptedMethods = array();
		/** @var Joinpoint $joinpoint */
		foreach ($proxy->getJoinpointsAdvices()->getKeys() as $joinpoint) {
			$interceptedMethods[] = $joinpoint->getReflectionMethod();
		}
		return $this->proxyCompiler->compileProxy($targetClass, $interceptedMethods);
}

}
