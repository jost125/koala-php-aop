<?php

namespace AOP\Aspect;

use AOP\Abstraction\Advice;
use AOP\Abstraction\Aspect;
use AOP\Abstraction\InterceptingMethod;
use AOP\Abstraction\Pointcut;
use AOP\Pointcut\PointcutExpression;
use AOP\TestCase;
use ReflectionClass;
use ReflectionMethod;

class SimpleAspectReflectionTest extends TestCase {

	/** @var SimpleAspectReflection */
	private $pregAspectReflection;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $adviceReflectionMock;

	protected function setUp() {
		$this->adviceReflectionMock = $this->createMock('\AOP\Advice\AdviceReflection');
		$this->pregAspectReflection = new SimpleAspectReflection($this->adviceReflectionMock);
	}

	public function testGetAspect() {
		$reflectionClass = new ReflectionClass('\AOP\Aspect\Bar');

		$this->adviceReflectionMock->expects($this->once())
			->method('getAdvices')
			->with($reflectionClass)
			->will($this->returnValue($this->getAdvicesFixtures()));

		$aspect = $this->pregAspectReflection->getAspect($reflectionClass);

		$this->assertEquals($this->getAspectExpectedResult(), $aspect);
	}

	public function getAspectExpectedResult() {
		return new Aspect(
			$this->getAdvicesFixtures()
		);
	}

	private function getAdvicesFixtures() {
		return array(new Advice(
			new Pointcut(new PointcutExpression('\AOP\Before("execution(public *(..))")')),
			new InterceptingMethod(new ReflectionMethod('\AOP\Aspect\Bar', 'beforeAdvice'))
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
