<?php

namespace AOP\ProxyFinder;

class SimpleProxyFinderTest extends \PHPUnit_Framework_TestCase {

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectReflectionMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $pointcutExpressionResolverMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyListFactoryMock;

	/** @var SimpleProxyFinder */
	private $simpleProxyFinder;

	protected function setUp() {
		$this->simpleProxyFinder = new SimpleProxyFinder(
			$this->mockAspectReflection(),
			$this->mockPointcutExpressionResolver(),
			$this->mockProxyListFactory()
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
			->with($aspectDefinitionsFixtures['fooAspect'])
			->will($this->returnValue($aspectFixtures));

		$this->pointcutExpressionResolverMock->expects($this->once())
			->method('findJoinpoints')
			->with('\SimpleProxyFinderTest\FooService', $this->getPointcutExpressionFixtures())
			->will($this->returnValue($this->getJoinpointsFixtures()));

		$proxyList = $this->simpleProxyFinder->findProxies($aspectDefinitionsFixtures, $this->getTargetDefinitionsFixtures());
		$this->assertEquals($this->getExpectedProxyList(), $proxyList);
	}

	public function getJoinpointsFixtures() {
		return array(
			new \AOP\Abstraction\Joinpoint('\SimpleProxyFinderTest\FooAspect', 'bar')
		);
	}

	private function getExpectedProxyList() {
		return new \AOP\Abstraction\ProxyList\ProxyArrayList();
	}

	private function getAspectDefinitionsFixtures() {
		return array(
			'fooAspect' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooAspect',
				'class' => '\SimpleProxyFinderTest\FooAspect',
			))
		);
	}

	private function getTargetDefinitionsFixtures() {
		return array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\SimpleProxyFinderTest\FooService',
			))
		);
	}

	private function getAspectFixtures() {
		return new \AOP\Abstraction\Aspect(array(
			new \AOP\Abstraction\Advice(new \AOP\Abstraction\Pointcut($this->getPointcutExpressionFixtures()))
		));
	}

	private function getPointcutExpressionFixtures() {
		return new \AOP\Pointcut\PointcutExpression('execution(public \SimpleProxyFinderTest\FooService::*(..))');
	}

	private function mockProxyListFactory() {
		$this->proxyListFactoryMock = $this->getMockBuilder('\AOP\Abstraction\ProxyListFactory')
			->disableOriginalConstructor()
			->getMock();

		return $this->proxyListFactoryMock;
	}

	private function mockPointcutExpressionResolver() {
		$this->pointcutExpressionResolverMock = $this->getMockBuilder('\AOP\PointcutExpressionResolver')
			->disableOriginalConstructor()
			->getMock();

		return $this->pointcutExpressionResolverMock;
	}

	private function mockAspectReflection() {
		$this->aspectReflectionMock = $this->getMockBuilder('\AOP\AspectReflection')
			->disableOriginalConstructor()
			->getMock();

		return $this->aspectReflectionMock;
	}

}
