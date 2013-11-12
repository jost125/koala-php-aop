<?php

namespace Koala\AOP\Proxy;

use InvalidArgumentException;
use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\Joinpoint;
use Koala\AOP\Abstraction\Pointcut\AfterPointcut;
use Koala\AOP\Abstraction\Pointcut\AfterReturningPointcut;
use Koala\AOP\Abstraction\Pointcut\AfterThrowingPointcut;
use Koala\AOP\Abstraction\Pointcut\AroundPointcut;
use Koala\AOP\Abstraction\Pointcut\BeforePointcut;
use Koala\AOP\Abstraction\Proxy;
use Koala\AOP\Abstraction\ProxyList;
use Koala\AOP\Interceptor\HashMapLoader;
use Koala\AOP\Interceptor\InterceptorTypes;
use Koala\AOP\Proxy\Compiling\CompiledProxy;
use Koala\AOP\Proxy\Compiling\ProxyCompiler;
use Koala\DI\Definition\Configuration\ArrayServiceDefinition;
use Koala\DI\Definition\Configuration\ServiceDefinition;
use Koala\IO\Storage\FileStorage;
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

					$className = get_class($advice->getPointcut());
					switch ($className) {
						case BeforePointcut::class:
							$interceptorType = InterceptorTypes::BEFORE;
							break;
						case AfterPointcut::class:
							$interceptorType = InterceptorTypes::AFTER;
							break;
						case AfterThrowingPointcut::class:
							$interceptorType = InterceptorTypes::AFTER_THROWING;
							break;
						case AfterReturningPointcut::class:
							$interceptorType = InterceptorTypes::AFTER_RETURNING;
							break;
						case AroundPointcut::class:
							$interceptorType = InterceptorTypes::AROUND;
							break;
						default:
							throw new InvalidArgumentException('Uknown pointcut class ' . $className);
					}

					if (!isset($interceptors[$reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()][$interceptorType])) {
						$interceptors[$reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()][$interceptorType] = [];
					}

					$interceptors[$reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()][$interceptorType][] = [$aspectDefinition->getServiceId(), $advice->getInterceptingMethod()->getMethodReflection()];
				}
			}

			$compiledProxy = $this->compileProxy($proxy);
			$key = str_replace('\\', '_', $compiledProxy->getClassName()) . '.php';
			$this->fileStorage->put($key, $compiledProxy->getCode());
			$this->fileStorage->includeOnce($key);

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
			$interceptedMethods[$joinpoint->getReflectionMethod()->getName()] = $joinpoint->getReflectionMethod();
		}
		return $this->proxyCompiler->compileProxy($targetClass, $interceptedMethods);
	}

}
