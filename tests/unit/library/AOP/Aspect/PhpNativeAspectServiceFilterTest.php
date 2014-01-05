<?php

namespace Koala\AOP\Aspect;

use Koala\AOP\TestCase;
use Koala\DI\Definition\Configuration\ArrayServiceDefinition;
use Koala\DI\Definition\Configuration\ServiceDefinition;
use Koala\Reflection\Annotation\Parsing\AnnotationResolver;
use ReflectionClass;

class PhpNativeAspectServiceFilterTest extends TestCase {

	/** @var PhpNativeAspectServiceFilter */
	private $phpNativeAspectServiceFilter;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $annotationResolverMock;

	protected function setUp() {
		$this->annotationResolverMock = $this->createMock(AnnotationResolver::class);
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
					'class' => Foo::class,
					'arguments' => array()
				)
			),
			'fooAspectService' => new ArrayServiceDefinition(
				array(
					'serviceId' => 'fooAspectService',
					'class' => FooAspect::class,
					'arguments' => array()
				)
			)
		);

		$expectedAspectServices = array(
			'fooAspectService' => new ArrayServiceDefinition(
				array(
					'serviceId' => 'fooAspectService',
					'class' => FooAspect::class,
					'arguments' => array()
				)
			)
		);

		$this->annotationResolverMock->expects($this->at(0))
			->method('hasClassAnnotation')
			->with(new ReflectionClass(Foo::class))
			->will($this->returnValue(false));

		$this->annotationResolverMock->expects($this->at(1))
			->method('hasClassAnnotation')
			->with(new ReflectionClass(FooAspect::class))
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
