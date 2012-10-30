<?php

namespace AOP\ProxyReplacer;

class SimpleProxyReplacerTest extends \PHPUnit_Framework_TestCase {

	/** @var SimpleProxyReplacer */
	private $simpleProxyReplacer;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectReflectionResolverMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	public $proxyBuilderMock;

	protected function setUp() {
		$this->simpleProxyReplacer = new SimpleProxyReplacer(
			$this->mockAspectReflectionResolver(),
			$this->mockProxyBuilder()
		);
	}

	public function testReplaceProxies_emptyDefinition() {
		$configuration = new \DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition(array('services' => array()));
		$replacedConfiguration = $this->simpleProxyReplacer->replaceProxies($configuration);
		$this->assertEquals($configuration, $replacedConfiguration);
	}

	public function testReplaceProxies_noAspects() {
		$configuration = new \DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition(array('services' => array(
			'fooService' => array(
				'class' => '\Foo',
				'arguments' => array()
			)
		)));

		$servicesDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'class' => '\Foo',
				'arguments' => array()
			))
		);

		$this->aspectReflectionResolverMock->expects($this->once())
			->method('filterAspectServices')
			->with($servicesDefinitions)
			->will($this->returnValue(array()));

		$this->proxyBuilderMock->expects($this->once())
			->method('buildProxies')
			->with(array(), $servicesDefinitions)
			->will($this->returnValue(array()));

		$replacedConfiguration = $this->simpleProxyReplacer->replaceProxies($configuration);

		$this->assertEquals($configuration, $replacedConfiguration);
	}

	private function mockAspectReflectionResolver() {
		$this->aspectReflectionResolverMock = $this->getMockBuilder('\AOP\AspectReflectionResolver')
			->disableOriginalConstructor()
			->getMock();

		return $this->aspectReflectionResolverMock;
	}

	private function mockProxyBuilder() {
		$this->proxyBuilderMock = $this->getMockBuilder('\AOP\ProxyBuilder')
			->disableOriginalConstructor()
			->getMock();

		return $this->proxyBuilderMock;
	}

}
