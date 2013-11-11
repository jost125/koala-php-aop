<?php

namespace Koala\Reflection\AnnotationResolver;

use Doctrine\Common\Annotations\AnnotationReader;
use Koala\AOP\Aspect;
use Koala\AOP\After;
use Koala\AOP\Before;
use Koala\AOP\TestCase;
use Koala\Reflection\Annotation\Parsing\AnnotationExpression;
use Koala\Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Koala\Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;
use ReflectionClass;

class DoctrineAnnotationResolverTest extends TestCase {

	/** @var DoctrineAnnotationResolver */
	private $annotationResolver;

	protected function setUp() {
		parent::setUp();
		$this->annotationResolver = new DoctrineAnnotationResolver(new AnnotationReader(), new SimpleAnnotationExpressionMatcher());
	}

	public function testHasClassAnnotation() {
		$reflectionClass = new ReflectionClass(Bar::class);
		$result = $this->annotationResolver->hasClassAnnotation($reflectionClass, new AnnotationExpression('Koala\AOP\Aspect'));
		$this->assertTrue($result);
	}

	public function testGetMethodsHavingAnnotation() {
		$reflectionClass = new ReflectionClass(Baz::class);
		$methods = $this->annotationResolver->getMethodsHavingAnnotation($reflectionClass, new AnnotationExpression('Koala\AOP\Before(..)'));
		$this->assertTrue(is_array($methods));
		$this->assertCount(1, $methods);
		$this->assertEquals($reflectionClass->getMethod('beforeAdvice'), $methods[0]);
	}

	public function testGetMethodAnnotations() {
		$reflectionClass = new ReflectionClass(Baz::class);
		$annotations = $this->annotationResolver->getMethodAnnotations($reflectionClass->getMethod('beforeAdvice'), new AnnotationExpression('Koala\AOP\Before(..)'));
		$this->assertTrue(is_array($annotations));
		$this->assertCount(1, $annotations);
		$this->assertEquals('Koala\AOP\Before(value="execution(public *(..))")', $annotations[0]->toExpression());
	}
}

/**
 * @Aspect
 */
class Bar {}

/**
 * @Aspect
 */
class Baz {
	/**
	 * @Before("execution(public *(..))")
	 */
	public function beforeAdvice() {}

	/**
	 * @After("execution(public *(..))")
	 */
	public function afterAdvice() {}
}
