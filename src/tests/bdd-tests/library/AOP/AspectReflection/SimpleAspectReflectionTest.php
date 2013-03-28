<?php

namespace AOP\AspectReflection;

use AOP\Abstraction\InterceptingMethod;
use AOP\Aspect;
use AOP\Before;
use AOP\TestCase;
use ReflectionClass;

class SimpleAspectReflectionTest extends TestCase {

	/** @var SimpleAspectReflection */
	private $pregAspectReflection;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $adviceReflectionMock;

	protected function setUp() {
		$this->adviceReflectionMock = $this->createMock('\AOP\AdviceReflection');
		$this->pregAspectReflection = new SimpleAspectReflection($this->adviceReflectionMock);
	}

	public function testGetAspect() {
		$reflectionClass = new ReflectionClass('\AOP\AspectReflection\Bar');

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
			new InterceptingMethod(new \ReflectionMethod('\AOP\AspectReflection\Bar', 'beforeAdvice'))
		));
	}

}

/**
 * @Aspect
 */
class Bar {

	/**
	 * @Before("execution(public *(..))")
	 */
	public function beforeAdvice() {

	}

}
