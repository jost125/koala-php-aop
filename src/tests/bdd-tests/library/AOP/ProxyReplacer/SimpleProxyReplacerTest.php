<?php

namespace AOP\ProxyReplacer;

use AOP\Proxy\SimpleProxyReplacer;
use AOP\TestCase;
use DI\Definition\ConfigurationDefinition\ArrayConfigurationDefinition;
use DI\Definition\ServiceDefinition\ArrayServiceDefinition;

class SimpleProxyReplacerTest extends TestCase {

	/** @var SimpleProxyReplacer */
	private $simpleProxyReplacer;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectServiceFilterMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	public $proxyBuilderMock;

	protected function setUp() {
		$this->aspectServiceFilterMock = $this->createMock('\AOP\AspectServiceFilter');
		$this->proxyBuilderMock = $this->createMock('\AOP\ProxyBuilder');
		$this->simpleProxyReplacer = new SimpleProxyReplacer(
			$this->aspectServiceFilterMock,
			$this->proxyBuilderMock
		);
	}

	public function testReplaceProxies_emptyDefinition() {
		$configuration = new ArrayConfigurationDefinition(array('services' => array()));
		$replacedConfiguration = $this->simpleProxyReplacer->replaceProxies($configuration);
		$this->assertEquals($configuration, $replacedConfiguration);
	}

	public function testReplaceProxies_noAspects() {
		$configuration = new ArrayConfigurationDefinition(array('services' => array(
			'fooService' => array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			)
		)));

		$servicesDefinitions = array(
			'fooService' => new ArrayServiceDefinition(array(
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
		$configuration = new ArrayConfigurationDefinition(array('services' => array(
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
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			)),
			'fooServiceAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooServiceAspect',
				'class' => '\FooAspect',
				'arguments' => array()
			))
		);

		$aspectServicesDefinitions = array(
			'fooServiceAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooServiceAspect',
				'class' => '\FooAspect',
				'arguments' => array()
			))
		);

		$nonAspectServicesDefinitions = array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\Foo',
				'arguments' => array()
			))
		);

		$proxyServicesDefinitions = array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => '\FooProxy',
				'arguments' => array()
			))
		);

		$expectedReplacedConfiguration = new ArrayConfigurationDefinition(array('services' => array(
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

}
