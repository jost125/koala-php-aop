<?php

namespace AOP\ProxyFinder;

use AOP\TestCase;
use ReflectionClass;

require_once __DIR__ . '/SimpleProxyFinderTest/FooService.php';
require_once __DIR__ . '/SimpleProxyFinderTest/FooAspect.php';

class SimpleProxyFinderTest extends TestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectReflectionMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $pointcutExpressionResolverMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyListFactoryMock;

	/** @var SimpleProxyFinder */
	private $simpleProxyFinder;

	protected function setUp() {
		$this->aspectReflectionMock = $this->createMock('\AOP\AspectReflection');
		$this->pointcutExpressionResolverMock = $this->createMock('\AOP\PointcutExpressionResolver');
		$this->proxyListFactoryMock = $this->createMock('\AOP\Abstraction\ProxyListFactory');
		$this->simpleProxyFinder = new SimpleProxyFinder(
			$this->aspectReflectionMock,
			$this->pointcutExpressionResolverMock,
			$this->proxyListFactoryMock
		);
	}

	public function testFindProxies() {
		$aspectDefinitionsFixtures = $this->getAspectDefinitionsFixtures();
		$aspectFixtures = $this->getAspectFixtures();

		$this->proxyListFactoryMock->expects($this->once())
			->method('createProxyList')
			->will($this->returnValue(new \AOP\Abstraction\ProxyList\ProxyArrayList()));

		$this->aspectReflectionMock->expects($this->once())
			->method('getAspect')
			->with(new ReflectionClass($aspectDefinitionsFixtures['fooAspect']->getClassName()))
			->will($this->returnValue($aspectFixtures));

		$this->pointcutExpressionResolverMock->expects($this->once())
			->method('findJoinpoints')
			->with(new ReflectionClass('\AOP\ProxyFinder\SimpleProxyFinderTest\FooService'), $this->getPointcutExpressionFixtures())
			->will($this->returnValue($this->getJoinpointsFixtures()));

		$proxyList = $this->simpleProxyFinder->findProxies($aspectDefinitionsFixtures, $this->getTargetDefinitionsFixtures());
		$this->assertEquals($this->getExpectedProxyList(), $proxyList);
	}

	public function getJoinpointsFixtures() {
		$reflectionClass = new ReflectionClass('\AOP\ProxyFinder\SimpleProxyFinderTest\FooAspect');
		$reflectionMethod = $reflectionClass->getMethod('bar');
		return array(
			new \AOP\Abstraction\Joinpoint($reflectionMethod)
		);
	}

	private function getExpectedProxyList() {
		return new \AOP\Abstraction\ProxyList\ProxyArrayList();
	}

	/**
	 * @return \DI\Definition\ServiceDefinition[]
	 */
	private function getAspectDefinitionsFixtures() {
		return array(
			'fooAspect' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooAspect',
				'class' => '\AOP\ProxyFinder\SimpleProxyFinderTest\FooAspect',
			))
		);
	}

	private function getTargetDefinitionsFixtures() {
		return array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\AOP\ProxyFinder\SimpleProxyFinderTest\FooService',
			))
		);
	}

	private function getAspectFixtures() {
		return new \AOP\Abstraction\Aspect(array(
			new \AOP\Abstraction\Advice(
				new \AOP\Abstraction\Pointcut($this->getPointcutExpressionFixtures()),
				'fooAdvice'
			)
		));
	}

	private function getPointcutExpressionFixtures() {
		return new \AOP\Pointcut\PointcutExpression('execution(public \SimpleProxyFinderTest\FooService::*(..))');
	}

}
