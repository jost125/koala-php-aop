<?php

namespace AOP\Proxy;

use AOP\Abstraction\Advice;
use AOP\Abstraction\InterceptingMethod;
use AOP\Abstraction\Joinpoint;
use AOP\Abstraction\Pointcut\BeforePointcut;
use AOP\Abstraction\Pointcut;
use AOP\Abstraction\Proxy;
use AOP\Abstraction\ProxyList;
use AOP\Before;
use AOP\Pointcut\PointcutExpression;
use AOP\Proxy\Compiling\ProxyCompiler;
use AOP\Proxy\SimpleProxyGenerator;
use AOP\TestCase;
use DI\Definition\Configuration\ArrayServiceDefinition;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;

require_once __DIR__ . '/../../../fixtures/FooAspect.php';
require_once __DIR__ . '/../../../fixtures/FooService.php';

class SimpleProxyGeneratorTest extends TestCase {

	/** @var SimpleProxyGenerator */
	private $simpleProxyGenerator;

	protected function setUp() {
		$this->simpleProxyGenerator = new SimpleProxyGenerator(new ProxyCompiler('___aop___', 'GeneratedAOPProxy'), '___aop___', 'generatedInterceptorLoader');
	}

	public function testGenerateProxies() {
		$proxies = $this->simpleProxyGenerator->generateProxies($this->getProxyListFixtures());
		$this->assertEquals($this->getExpected(), $proxies);
	}

	private function getProxyListFixtures() {
		$serviceReflectionClass = new ReflectionClass('FooService');
		$aspectReflectionClass = new ReflectionClass('FooAspect');

		$proxyList = new ProxyList();

		$joinpoint = new Joinpoint($serviceReflectionClass->getMethod('foo'));

		$advice = new Advice(
			new Pointcut(new PointcutExpression('\AOP\Before')),
			new InterceptingMethod($aspectReflectionClass->getMethod('fooAdvice'))
		);

		$joinpointsAdvices = new SplObjectStorage();
		$joinpointsAdvices->offsetSet($joinpoint, new SplObjectStorage());
		$joinpointsAdvices->offsetGet($joinpoint)->attach($advice);

		$proxyList->addProxy(new Proxy(
			$joinpointsAdvices,
			new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\FooService',
			))
		));

		return $proxyList;
	}

	private function getExpected() {
		return array(
			new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => 'GeneratedAOPProxy\FooService',
				'arguments' => array(),
				'setup' => array(
					'___aop___setInterceptorLoader' => array(
						array('service', 'generatedInterceptorLoader')
					),
				),
			))
		);
	}

}
