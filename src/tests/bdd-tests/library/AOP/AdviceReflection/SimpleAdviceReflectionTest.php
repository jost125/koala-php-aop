<?php

namespace AOP\AdviceReflection;

use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\InterceptingMethod;
use Koala\AOP\Abstraction\Pointcut\BeforePointcut;
use Koala\AOP\Advice\SimpleAdviceReflection;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\AOP\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Koala\Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;
use Koala\Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Koala\AOP\Before;
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
	 * @Before("execution(public *(..))")
	 */
	public function fooAdvice() {}
}
