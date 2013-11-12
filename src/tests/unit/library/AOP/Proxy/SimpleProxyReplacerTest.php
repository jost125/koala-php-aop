<?php

namespace Koala\AOP\Proxy;

use FooAspect;
use Koala\AOP\Aspect\AspectServiceFilter;
use Koala\AOP\Aspect\Foo;
use Koala\AOP\TestCase;
use Koala\DI\Definition\Configuration\ArrayConfigurationDefinition;
use Koala\DI\Definition\Configuration\ArrayServiceDefinition;

class SimpleProxyReplacerTest extends TestCase {

	/** @var SimpleProxyReplacer */
	private $simpleProxyReplacer;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $aspectServiceFilterMock;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	public $proxyBuilderMock;

	protected function setUp() {
		$this->aspectServiceFilterMock = $this->createMock(AspectServiceFilter::class);
		$this->proxyBuilderMock = $this->createMock(ProxyBuilder::class);
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
				'class' => Foo::class,
				'arguments' => array()
			)
		)));

		$servicesDefinitions = array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => Foo::class,
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
				'class' => Foo::class,
				'arguments' => array()
			),
			'fooServiceAspect' => array(
				'serviceId' => 'fooServiceAspect',
				'class' => FooAspect::class,
				'arguments' => array()
			)
		)));

		$servicesDefinitions = array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => Foo::class,
				'arguments' => array()
			)),
			'fooServiceAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooServiceAspect',
				'class' => FooAspect::class,
				'arguments' => array()
			))
		);

		$aspectServicesDefinitions = array(
			'fooServiceAspect' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooServiceAspect',
				'class' => FooAspect::class,
				'arguments' => array()
			))
		);

		$nonAspectServicesDefinitions = array(
			'fooService' => new ArrayServiceDefinition(array(
				'serviceId' => 'fooService',
				'class' => Foo::class,
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
				'arguments' => array(),
				'setup' => [],
			),
			'fooServiceAspect' => array(
				'serviceId' => 'fooServiceAspect',
				'class' => FooAspect::class,
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
