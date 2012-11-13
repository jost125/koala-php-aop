<?php

namespace AOP\ProxyBuilder;

class SimpleProxyBuilderTest extends \PHPUnit_Framework_TestCase {

	/** @var SimpleProxyBuilder */
	private $simpleProxyBuilder;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyFinderMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyGenerator;

	protected function setUp() {
		$this->simpleProxyBuilder = new SimpleProxyBuilder(
			$this->mockProxyGenerator(),
			$this->mockProxyFinder()
		);
	}

	public function testBuildProxies_emptyAspectsAndTargets() {
		$aspectServiceDefinitions = array();
		$possibleTargetServiceDefinitions = array();

		$builtProxies = $this->simpleProxyBuilder->buildProxies($aspectServiceDefinitions, $possibleTargetServiceDefinitions);

		$this->assertEquals(array(), $builtProxies);
	}

	public function testBuildProxies_emptyAspects() {
		$aspectServiceDefinitions = array();
		$possibleTargetServiceDefinitions = $this->getPossibleTargetServiceDefinitionFixtures();

		$builtProxies = $this->simpleProxyBuilder->buildProxies($aspectServiceDefinitions, $possibleTargetServiceDefinitions);

		$this->assertEquals(array(), $builtProxies);
	}

	public function testBuildProxies_emptyTargets() {
		$aspectServiceDefinitions = $this->getAspectServiceDefinitionFixtures();
		$possibleTargetServiceDefinitions = array();

		$builtProxies = $this->simpleProxyBuilder->buildProxies($aspectServiceDefinitions, $possibleTargetServiceDefinitions);

		$this->assertEquals(array(), $builtProxies);
	}

	public function testBuildProxies() {
		$aspectDefinitions = $this->getAspectServiceDefinitionFixtures();
		$serviceDefinitions = $this->getPossibleTargetServiceDefinitionFixtures();

		$this->proxyFinderMock->expects($this->once())
			->method('findProxies')
			->with($aspectDefinitions, $serviceDefinitions)
			->will($this->returnValue($this->mockProxyList()));

		$this->proxyGenerator->expects($this->once())
			->method('generateProxies')
			->with($this->mockProxyList())
			->will($this->returnValue($this->getExpectedBuiltProxies()));

		$builtProxies = $this->simpleProxyBuilder->buildProxies($aspectDefinitions, $serviceDefinitions);

		$this->assertEquals($this->getExpectedBuiltProxies(), $builtProxies);
	}

	private function mockProxyFinder() {
		$this->proxyFinderMock = $this->getMockBuilder('\AOP\ProxyFinder')
			->disableOriginalConstructor()
			->getMock();

		return $this->proxyFinderMock;
	}

	private function mockProxyGenerator() {
		$this->proxyGenerator = $this->getMockBuilder('\AOP\ProxyGenerator')
			->disableOriginalConstructor()
			->getMock();

		return $this->proxyGenerator;
	}

	private function getPossibleTargetServiceDefinitionFixtures() {
		return array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'class' => '\SimpleProxyBuilderTest\FooService',
				'arguments' => array(
					array('service' => 'fooDependencyService'),
				),
			)),
			'fooDependencyService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'class' => '\SimpleProxyBuilderTest\FooDependencyService',
			)),
		);
	}

	private function getAspectServiceDefinitionFixtures() {
		return array(
			'fooAspect' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'class' => '\SimpleProxyBuilderTest\FooAspect',
			)),
		);
	}

	private function getExpectedBuiltProxies() {
		return array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'class' => '\AOPGeneratedProxy\SimpleProxyBuilderTest\FooService',
				'arguments' => array(
					array('service' => 'fooDependencyService'),
				),
			)),
		);
	}

	private function mockProxyList() {
		$proxyListMock = $this->getMockBuilder('\AOP\Abstraction\ProxyList')
			->getMock();

		return $proxyListMock;
	}

}
