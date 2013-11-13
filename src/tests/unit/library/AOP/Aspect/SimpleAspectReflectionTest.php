<?php

namespace Koala\AOP\Aspect;

use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\Aspect;
use Koala\AOP\Abstraction\InterceptingMethod;
use Koala\AOP\Abstraction\Pointcut;
use Koala\AOP\Advice\AdviceReflection;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\AOP\TestCase;
use ReflectionClass;
use ReflectionMethod;

class SimpleAspectReflectionTest extends TestCase {

	/** @var SimpleAspectReflection */
	private $pregAspectReflection;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $adviceReflectionMock;

	protected function setUp() {
		$this->adviceReflectionMock = $this->createMock(AdviceReflection::class);
		$this->pregAspectReflection = new SimpleAspectReflection($this->adviceReflectionMock);
	}

	public function testGetAspect() {
		$reflectionClass = new ReflectionClass(Bar::class);

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
			new InterceptingMethod(new ReflectionMethod(Bar::class, 'beforeAdvice'))
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
