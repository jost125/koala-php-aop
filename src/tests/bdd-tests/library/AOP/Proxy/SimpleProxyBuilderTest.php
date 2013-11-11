<?php

namespace AOP\Proxy;

use AOP\Proxy\SimpleProxyBuilder;
use AOP\TestCase;
use DI\Definition\Configuration\ArrayServiceDefinition;

class SimpleProxyBuilderTest extends TestCase {

	/** @var SimpleProxyBuilder */
	private $simpleProxyBuilder;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyFinderMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyGeneratorMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $proxyListMock;

	protected function setUp() {
		$this->proxyFinderMock = $this->createMock('\AOP\Proxy\ProxyFinder');
		$this->proxyGeneratorMock = $this->createMock('\AOP\Proxy\ProxyGenerator');
		$this->proxyListMock = $this->createMock('\AOP\Abstraction\ProxyList');
		$this->simpleProxyBuilder = new SimpleProxyBuilder(
			$this->proxyGeneratorMock,
			$this->proxyFinderMock
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
			->will($this->returnValue($this->proxyListMock));

		$this->proxyGeneratorMock->expects($this->once())
			->method('generateProxies')
			->with($this->proxyListMock)
			->will($this->returnValue($this->getExpectedBuiltProxies()));

		$builtProxies = $this->simpleProxyBuilder->buildProxies($aspectDefinitions, $serviceDefinitions);

		$this->assertEquals($this->getExpectedBuiltProxies(), $builtProxies);
	}

	private function getPossibleTargetServiceDefinitionFixtures() {
		return array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\SimpleProxyBuilderTest\FooService',
				'arguments' => array(
					array('service' => 'fooDependencyService'),
				),
			)),
			'fooDependencyService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooDependencyService',
				'class' => '\SimpleProxyBuilderTest\FooDependencyService',
			)),
		);
	}

	private function getAspectServiceDefinitionFixtures() {
		return array(
			'fooAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooAspect',
				'class' => '\SimpleProxyBuilderTest\FooAspect',
			)),
		);
	}

	private function getExpectedBuiltProxies() {
		return array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\AOPGeneratedProxy\SimpleProxyBuilderTest\FooService',
				'arguments' => array(
					array('service' => 'fooDependencyService'),
				),
			)),
		);
	}

}
