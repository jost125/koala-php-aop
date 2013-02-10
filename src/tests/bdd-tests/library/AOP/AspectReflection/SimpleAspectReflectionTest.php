<?php

namespace AOP\AspectReflection;

use AOP\TestCase;
use ReflectionClass;

require_once __DIR__ . '/SimpleAspectReflectionTest/BarAspect.php';

class SimpleAspectReflectionTest extends TestCase {

	/** @var SimpleAspectReflection */
	private $pregAspectReflection;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $adviceReflectionMock;

	protected function setUp() {
		$this->pregAspectReflection = new SimpleAspectReflection($this->mockAdviceReflection());
	}

	public function testGetAspect() {
		$reflectionClass = new ReflectionClass('AOP\AspectReflection\SimpleAspectReflectionTest\BarAspect');

		$this->adviceReflectionMock->expects($this->once())
			->method('getAdvices')
			->with($reflectionClass)
			->will($this->returnValue($this->getAdvicesFixtures()));

		$aspect = $this->pregAspectReflection->getAspect($reflectionClass);

		$this->assertEquals($this->getAspectExpectedResult(), $aspect);
	}

	public function getAspectExpectedResult() {
		return new \AOP\Abstraction\Aspect(
			$this->getAdvicesFixtures()
		);
	}

	private function getAdvicesFixtures() {
		return array(new \AOP\Abstraction\Advice(
			new \AOP\Abstraction\Pointcut(new \AOP\Pointcut\PointcutExpression('\AOP\Before("execution(public *(..))")')),
			'beforeAdvice'
		));
	}

	private function mockAdviceReflection() {
		$this->adviceReflectionMock = $this->getMockBuilder('\AOP\AdviceReflection')
			->disableOriginalConstructor()
			->getMock();

		return $this->adviceReflectionMock;
	}

}
