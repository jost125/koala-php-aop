<?php

namespace AOP\Proxy;

use AOP\Abstraction\Advice;
use AOP\Abstraction\Aspect;
use AOP\Abstraction\InterceptingMethod;
use AOP\Abstraction\Joinpoint;
use AOP\Abstraction\Pointcut;
use AOP\Abstraction\Proxy;
use AOP\Abstraction\ProxyList;
use AOP\Pointcut\PointcutExpression;
use AOP\TestCase;
use DI\Definition\Configuration\ArrayServiceDefinition;
use DI\Definition\Configuration\ServiceDefinition;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;

require_once __DIR__ . '/../../../fixtures/FooAspect.php';
require_once __DIR__ . '/../../../fixtures/FooService.php';

class SimpleProxyFinderTest extends TestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectReflectionMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $pointcutExpressionResolverMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyListFactory;

	/** @var SimpleProxyFinder */
	private $simpleProxyFinder;

	protected function setUp() {
		$this->aspectReflectionMock = $this->createMock('\AOP\Aspect\AspectReflection');
		$this->pointcutExpressionResolverMock = $this->createMock('\AOP\Pointcut\PointcutExpressionResolver');
		$this->simpleProxyFinder = new SimpleProxyFinder(
			$this->aspectReflectionMock,
			$this->pointcutExpressionResolverMock,
			$this->proxyListFactory
		);
	}

	public function testFindProxies() {
		$aspectDefinitionsFixtures = $this->getAspectDefinitionsFixtures();
		$aspectFixtures = $this->getAspectFixtures();

		$this->aspectReflectionMock->expects($this->once())
			->method('getAspect')
			->with(new ReflectionClass($aspectDefinitionsFixtures['fooAspect']->getClassName()))
			->will($this->returnValue($aspectFixtures));

		$this->pointcutExpressionResolverMock->expects($this->once())
			->method('findJoinpoints')
			->with(new ReflectionClass('FooService'), $this->getPointcutExpressionFixtures())
			->will($this->returnValue($this->getJoinpointsFixtures()));

		$proxyList = $this->simpleProxyFinder->findProxies($aspectDefinitionsFixtures, $this->getTargetDefinitionsFixtures());
		$it = $proxyList->getIterator();
		foreach ($this->getExpectedProxyList() as $expectedProxy) {
			$proxy = $it->current();
			$this->assertEquals($expectedProxy->getTargetDefinition(), $proxy->getTargetDefinition());

			$it2 = $proxy->getJoinpointsAdvices();
			$it2->rewind();
			foreach ($expectedProxy->getJoinpointsAdvices() as $expectedJoinpointsAdvices) {
				$joinpointsAdvices = $it2->current();
				$this->assertEquals($expectedJoinpointsAdvices, $joinpointsAdvices);
				$it2->next();
			}
			$it->next();
		}
	}

	public function getJoinpointsFixtures() {
		$reflectionClass = new ReflectionClass('FooAspect');
		$reflectionMethod = $reflectionClass->getMethod('fooAdvice');
		return array(
			new Joinpoint($reflectionMethod)
		);
	}

	private function getExpectedProxyList() {
		$reflectionClass = new ReflectionClass('FooAspect');
		$reflectionMethod = $reflectionClass->getMethod('fooAdvice');
		$joinpoint = new Joinpoint($reflectionMethod);

		$advice = new Advice(
			new Pointcut($this->getPointcutExpressionFixtures()),
			new InterceptingMethod(new ReflectionMethod('FooAspect', 'fooAdvice'))
		);

		$joinpointsAdvices = new SplObjectStorage();
		$joinpointsAdvices->offsetSet($joinpoint, new SplObjectStorage());
		$joinpointsAdvices->offsetGet($joinpoint)->attach($advice);

		$targetDefinition = new ArrayServiceDefinition(array(
			'serviceId' => 'fooService',
			'class' => 'FooService',
		));

		$proxyArrayList = new ProxyList();
		$proxyArrayList->addProxy(new Proxy($joinpointsAdvices, $targetDefinition));
		return $proxyArrayList;
	}

	/**
	 * @return ServiceDefinition[]
	 */
	private function getAspectDefinitionsFixtures() {
		return array(
			'fooAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooAspect',
				'class' => 'FooAspect',
			))
		);
	}

	private function getTargetDefinitionsFixtures() {
		return array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => 'FooService',
			))
		);
	}

	private function getAspectFixtures() {
		return new Aspect(array(
			new Advice(
				new Pointcut($this->getPointcutExpressionFixtures()),
				new InterceptingMethod(new ReflectionMethod('FooAspect', 'fooAdvice'))
			)
		));
	}

	private function getPointcutExpressionFixtures() {
		return new PointcutExpression('\AOP\Around("execution(public \SimpleProxyFinderTest\FooService::*(..))")');
	}

}
