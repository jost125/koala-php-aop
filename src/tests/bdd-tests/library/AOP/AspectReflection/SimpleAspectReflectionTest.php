<?php

namespace AOP\AspectReflection;

class SimpleAspectReflectionTest extends \PHPUnit_Framework_TestCase {

	/** @var SimpleAspectReflection */
	private $pregAspectReflection;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $adviceReflectionMock;

	protected function setUp() {
		$this->pregAspectReflection = new SimpleAspectReflection($this->mockAdviceReflection());
	}

	public function testGetAspect() {
		$className = 'PregAspectReflectionTest\FooAspect';

		$this->adviceReflectionMock->expects($this->once())
			->method('getAdvices')
			->with($className)
			->will($this->returnValue($this->getAdvicesFixtures()));

		$aspect = $this->pregAspectReflection->getAspect($className);

		$this->assertEquals($this->getAspectExpectedResult(), $aspect);
	}

	public function getAspectExpectedResult() {
		return new \AOP\Abstraction\Aspect(
			$this->getAdvicesFixtures()
		);
	}

	private function getAdvicesFixtures() {
		return array(new \AOP\Abstraction\Advice(
			new \AOP\Abstraction\Pointcut(new \AOP\Pointcut\PointcutExpression('\AOP\Before("execution(public *(..))")'))
		));
	}

	private function mockAdviceReflection() {
		$this->adviceReflectionMock = $this->getMockBuilder('\AOP\AdviceReflection')
			->disableOriginalConstructor()
			->getMock();

		return $this->adviceReflectionMock;
	}

}
