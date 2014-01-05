<?php

namespace AOP\AdviceReflection;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\InterceptingMethod;
use Koala\AOP\Abstraction\Pointcut\AfterPointcut;
use Koala\AOP\Abstraction\Pointcut\AfterReturningPointcut;
use Koala\AOP\Abstraction\Pointcut\AfterThrowingPointcut;
use Koala\AOP\Abstraction\Pointcut\AroundPointcut;
use Koala\AOP\Abstraction\Pointcut\BeforePointcut;
use Koala\AOP\Advice\SimpleAdviceReflection;
use Koala\AOP\Before;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\AOP\TestCase;
use Koala\Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Koala\Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;
use Koala\Reflection\Annotation\SimpleAnnotation;
use ReflectionClass;
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

	/**
	 * @dataProvider createPoincutExpressionFixtures
	 */
	public function testCreatePoincutExpression($annotation, $expectedPointcutExpression) {
		$method = $this->getAccesibleMethod($this->simpleAdviceReflection, 'createPoincutExpression');
		$this->assertEquals(
			$expectedPointcutExpression,
			$method->invokeArgs($this->simpleAdviceReflection, [$annotation])
		);
	}

	public function createPoincutExpressionFixtures() {
		return [
			[new SimpleAnnotation('Koala\AOP\Before', ['value' => 'foo']), new BeforePointcut(new PointcutExpression('foo'))],
			[new SimpleAnnotation('Koala\AOP\After', ['value' => 'foo']), new AfterPointcut(new PointcutExpression('foo'))],
			[new SimpleAnnotation('Koala\AOP\AfterReturning', ['value' => 'foo']), new AfterReturningPointcut(new PointcutExpression('foo'))],
			[new SimpleAnnotation('Koala\AOP\AfterThrowing', ['value' => 'foo']), new AfterThrowingPointcut(new PointcutExpression('foo'))],
			[new SimpleAnnotation('Koala\AOP\Around', ['value' => 'foo']), new AroundPointcut(new PointcutExpression('foo'))],
		];
	}

	public function testCreatePoincutExpression_none() {
		$method = $this->getAccesibleMethod($this->simpleAdviceReflection, 'createPoincutExpression');
		$annotation = new SimpleAnnotation('Koala\AOP\Foo', ['value' => 'foo']);

		try {
			$method->invokeArgs($this->simpleAdviceReflection, [$annotation]);
			$this->fail('Exception expected');
		} catch (InvalidArgumentException $ex) {
			$this->assertEquals('Uknown pointcut ' . $annotation->getName(), $ex->getMessage());
		}
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
