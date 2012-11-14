<?php

namespace AOP\ProxyReplacer;

class SimpleProxyReplacerTest extends \PHPUnit_Framework_TestCase {

	/** @var SimpleProxyReplacer */
	private $simpleProxyReplacer;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectServiceFilterMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	public $proxyBuilderMock;

	protected function setUp() {
		$this->simpleProxyReplacer = new SimpleProxyReplacer(
			$this->mockAspectServiceFilter(),
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
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			)
		)));

		$servicesDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			))
		);

		$this->aspectServiceFilterMock->expects($this->once())
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

	public function testReplaceProxies() {
		$configuration = new \DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition(array('services' => array(
			'fooService' => array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			),
			'fooServiceAspect' => array(
				'serviceId' => 'fooServiceAspect',
				'class' => '\FooAspect',
				'arguments' => array()
			)
		)));

		$servicesDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			)),
			'fooServiceAspect' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooServiceAspect',
				'class' => '\FooAspect',
				'arguments' => array()
			))
		);

		$aspectServicesDefinitions = array(
			'fooServiceAspect' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooServiceAspect',
				'class' => '\FooAspect',
				'arguments' => array()
			))
		);

		$nonAspectServicesDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			))
		);

		$proxyServicesDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\FooProxy',
				'arguments' => array()
			))
		);

		$expectedReplacedConfiguration = new \DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition(array('services' => array(
			'fooService' => array(
				'serviceId' => 'fooService',
				'class' => '\FooProxy',
				'arguments' => array()
			),
			'fooServiceAspect' => array(
				'serviceId' => 'fooServiceAspect',
				'class' => '\FooAspect',
				'arguments' => array()
			)
		)));

		$this->aspectServiceFilterMock->expects($this->once())
			->method('filterAspectServices')
			->with($servicesDefinitions)
			->will($this->returnValue($aspectServicesDefinitions));

		$this->proxyBuilderMock->expects($this->once())
			->method('buildProxies')
			->with($aspectServicesDefinitions, $nonAspectServicesDefinitions)
			->will($this->returnValue($proxyServicesDefinitions));

		$replacedConfiguration = $this->simpleProxyReplacer->replaceProxies($configuration);

		$this->assertEquals($expectedReplacedConfiguration, $replacedConfiguration);
	}

	private function mockAspectServiceFilter() {
		$this->aspectServiceFilterMock = $this->getMockBuilder('\AOP\AspectServiceFilter')
			->disableOriginalConstructor()
			->getMock();

		return $this->aspectServiceFilterMock;
	}

	private function mockProxyBuilder() {
		$this->proxyBuilderMock = $this->getMockBuilder('\AOP\ProxyBuilder')
			->disableOriginalConstructor()
			->getMock();

		return $this->proxyBuilderMock;
	}

}
