<?php

namespace AOP\AdviceReflection;

use AOP\TestCase;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Reflection\AnnotationExpressionMatcher\SimpleAnnotationExpressionMatcher;
use Reflection\AnnotationResolver\DoctrineAnnotationResolver;

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
			new \AOP\Abstraction\Advice(
				new \AOP\Abstraction\Pointcut\BeforePointcut(new \AOP\Pointcut\PointcutExpression('execution(public *(..))'))
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
