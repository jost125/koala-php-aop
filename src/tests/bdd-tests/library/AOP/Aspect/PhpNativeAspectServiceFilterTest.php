<?php

namespace AOP\Aspect;

use AOP\Aspect\PhpNativeAspectServiceFilter;
use AOP\TestCase;
use DI\Definition\ServiceDefinition\ArrayServiceDefinition;
use DI\Definition\ServiceDefinition;
use ReflectionClass;

class PhpNativeAspectServiceFilterTest extends TestCase {

	/** @var PhpNativeAspectServiceFilter */
	private $phpNativeAspectServiceFilter;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $annotationResolverMock;

	protected function setUp() {
		$this->annotationResolverMock = $this->createMock('\Reflection\AnnotationResolver');
		$this->phpNativeAspectServiceFilter = new PhpNativeAspectServiceFilter(
			$this->annotationResolverMock
		);
	}

	public function testFilterAspectServices() {
		/** @var $serviceDefinitions ServiceDefinition[] */
		$serviceDefinitions = array(
			'fooService' => new ArrayServiceDefinition(
				array(
					'serviceId' => 'fooService',
					'class' => '\AOP\Aspect\Foo',
					'arguments' => array()
				)
			),
			'fooAspectService' => new ArrayServiceDefinition(
				array(
					'serviceId' => 'fooAspectService',
					'class' => '\AOP\Aspect\FooAspect',
					'arguments' => array()
				)
			)
		);

		$expectedAspectServices = array(
			'fooAspectService' => new ArrayServiceDefinition(
				array(
					'serviceId' => 'fooAspectService',
					'class' => '\AOP\Aspect\FooAspect',
					'arguments' => array()
				)
			)
		);

		$this->annotationResolverMock->expects($this->at(0))
			->method('hasClassAnnotation')
			->with(new ReflectionClass('\AOP\Aspect\Foo'))
			->will($this->returnValue(false));

		$this->annotationResolverMock->expects($this->at(1))
			->method('hasClassAnnotation')
			->with(new ReflectionClass('\AOP\Aspect\FooAspect'))
			->will($this->returnValue(true));

		$filteredAspectServices = $this->phpNativeAspectServiceFilter->filterAspectServices($serviceDefinitions);

		$this->assertEquals($expectedAspectServices, $filteredAspectServices);
	}

}

class Foo {

}

/**
 * @\AOP\Aspect
 */
class FooAspect {

}
