<?php

namespace AOP\AdviceReflection;

use AOP\Abstraction\Advice;
use AOP\Abstraction\InterceptingMethod;
use AOP\Abstraction\Pointcut\BeforePointcut;
use AOP\Advice\SimpleAdviceReflection;
use AOP\Pointcut\PointcutExpression;
use AOP\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;
use Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use ReflectionMethod;

class SimpleAdviceReflectionTest extends TestCase {

	/** @var SimpleAdviceReflection */
	private $simpleAdviceReflection;

	/** @var ReflectionClass */
	private $reflectionClass;

	protected function setUp() {
		$this->simpleAdviceReflection = new SimpleAdviceReflection(new DoctrineAnnotationResolver(new AnnotationReader(), new SimpleAnnotationExpressionMatcher()));
		$this->reflectionClass = new ReflectionClass('\AOP\AdviceReflection\Bar');
	}

	public function testGetAdvices() {
		$advices = $this->simpleAdviceReflection->getAdvices($this->reflectionClass);
		$this->assertEquals($this->getExpectedAdvices(), $advices);
	}

	private function getExpectedAdvices() {
		return array(
			new Advice(
				new BeforePointcut(new PointcutExpression('execution(public *(..))')),
				new InterceptingMethod(new ReflectionMethod('\AOP\AdviceReflection\Bar', 'fooAdvice'))
			)
		);
	}

}

class Bar {
	/**
	 * @\AOP\Before("execution(public *(..))")
	 */
	public function fooAdvice() {}
}
