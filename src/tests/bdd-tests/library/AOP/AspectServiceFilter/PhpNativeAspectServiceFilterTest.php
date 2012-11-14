<?php

namespace AOP\AspectServiceFilter;

class PhpNativeAspectServiceFilterTest extends \PHPUnit_Framework_TestCase {

	/** @var PhpNativeAspectServiceFilter */
	private $phpNativeAspectServiceFilter;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $annotationResolverMock;

	protected function setUp() {
		$this->phpNativeAspectServiceFilter = new PhpNativeAspectServiceFilter(
			$this->mockAnnotationResolver()
		);
	}

	public function testFilterAspectServices() {
		require_once __DIR__ . '/PhpNativeAspectServiceFilterTest/Foo.php';
		require_once __DIR__ . '/PhpNativeAspectServiceFilterTest/FooAspect.php';

		/** @var $serviceDefinitions \DI\Definition\ServiceDefinition[] */
		$serviceDefinitions = array(
			'fooService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(
				array(
					'class' => '\PhpNativeAspectReflectionResolverTest\Foo',
					'arguments' => array()
				)
			),
			'fooAspectService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(
				array(
					'class' => '\PhpNativeAspectReflectionResolverTest\FooAspect',
					'arguments' => array()
				)
			)
		);

		$expectedAspectServices = array(
			'fooAspectService' => new \DI\Definition\ServiceDefinition\ArrayServiceDefinition(
				array(
					'class' => '\PhpNativeAspectReflectionResolverTest\FooAspect',
					'arguments' => array()
				)
			)
		);

		$this->annotationResolverMock->expects($this->at(0))
			->method('hasClassAnnotation')
			->with('\PhpNativeAspectReflectionResolverTest\Foo')
			->will($this->returnValue(false));

		$this->annotationResolverMock->expects($this->at(1))
			->method('hasClassAnnotation')
			->with('\PhpNativeAspectReflectionResolverTest\FooAspect')
			->will($this->returnValue(true));

		$filteredAspectServices = $this->phpNativeAspectServiceFilter->filterAspectServices($serviceDefinitions);

		$this->assertEquals($expectedAspectServices, $filteredAspectServices);
	}

	private function mockAnnotationResolver() {
		$this->annotationResolverMock = $this->getMockBuilder('\Reflection\AnnotationResolver')
			->disableOriginalConstructor()
			->getMock();

		return $this->annotationResolverMock;
	}

}
