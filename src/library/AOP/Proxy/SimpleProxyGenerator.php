<?php

namespace AOP\Proxy;

use AOP\Abstraction\Joinpoint;
use AOP\Abstraction\Proxy;
use AOP\Abstraction\ProxyList;
use AOP\Proxy\Compiling\CompiledProxy;
use AOP\Proxy\Compiling\ProxyCompiler;
use AOP\Proxy\ProxyGenerator;
use DI\Definition\Argument\SetupMethod;
use DI\Definition\Configuration\ArrayServiceDefinition;
use DI\Definition\Configuration\ServiceDefinition;
use ReflectionClass;

class SimpleProxyGenerator implements ProxyGenerator {

	private $proxyCompiler;
	private $proxyMemberPrefix;
	private $interceptorLoaderId;

	public function __construct(ProxyCompiler $proxyCompiler, $proxyMemberPrefix, $interceptorLoaderId) {
		$this->proxyCompiler = $proxyCompiler;
		$this->proxyMemberPrefix = $proxyMemberPrefix;
		$this->interceptorLoaderId = $interceptorLoaderId;
	}

	/**
	 * @param ProxyList $proxyAbstractionList
	 * @return ServiceDefinition[]
	 */
	public function generateProxies(ProxyList $proxyAbstractionList) {
		$proxyDefinitions = array();

		/** @var $proxy Proxy */
		foreach ($proxyAbstractionList as $proxy) {
			$compiledProxy = $this->compileProxy($proxy);

			$arguments = $proxy->getTargetDefinition()->hasConstructorArguments() ? $proxy->getTargetDefinition()->getConstructorArguments() : array();

			$setupMethods = $proxy->getTargetDefinition()->hasSetupMethods() ? $proxy->getTargetDefinition()->getSetupMethods() : array();
			$setupMethods[$this->proxyMemberPrefix . 'setInterceptorLoader'] = array(
				array('service', $this->interceptorLoaderId),
			);

			$definition = new ArrayServiceDefinition(array(
				'serviceId' => $proxy->getTargetDefinition()->getServiceId(),
				'class' => $compiledProxy->getClassName(),
				'arguments' => $arguments,
				'setup' => $setupMethods,
			));

			$proxyDefinitions[] = $definition;
		}

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
