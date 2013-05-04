<?php

namespace Reflection\AnnotationResolver;

use AOP\TestCase;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Reflection\Annotation\Parsing\DoctrineAnnotationResolver;
use Reflection\Annotation\Parsing\AnnotationExpression;
use ReflectionClass;
use Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;
use Doctrine\Common\Annotations\AnnotationReader;

class DoctrineAnnotationResolverTest extends TestCase {

	/** @var DoctrineAnnotationResolver */
	private $annotationResolver;

	protected function setUp() {
		parent::setUp();
		$this->annotationResolver = new DoctrineAnnotationResolver(new AnnotationReader(), new SimpleAnnotationExpressionMatcher());
	}

	public function testHasClassAnnotation() {
		$reflectionClass = new ReflectionClass('\Reflection\AnnotationResolver\Bar');
		$result = $this->annotationResolver->hasClassAnnotation($reflectionClass, new AnnotationExpression('AOP\Aspect'));
		$this->assertTrue($result);
	}

	public function testGetMethodsHavingAnnotation() {
		$reflectionClass = new ReflectionClass('\Reflection\AnnotationResolver\Baz');
		$methods = $this->annotationResolver->getMethodsHavingAnnotation($reflectionClass, new AnnotationExpression('AOP\Before(..)'));
		$this->assertTrue(is_array($methods));
		$this->assertCount(1, $methods);
		$this->assertEquals($reflectionClass->getMethod('beforeAdvice'), $methods[0]);
	}

	public function testGetMethodAnnotations() {
		$reflectionClass = new ReflectionClass('\Reflection\AnnotationResolver\Baz');
		$annotations = $this->annotationResolver->getMethodAnnotations($reflectionClass->getMethod('beforeAdvice'), new AnnotationExpression('AOP\Before(..)'));
		$this->assertTrue(is_array($annotations));
		$this->assertCount(1, $annotations);
		$this->assertEquals('AOP\Before(value="execution(public *(..))")', $annotations[0]->toExpression());
	}
}

/**
 * @\AOP\Aspect
 */
class Bar {}

/**
 * @\AOP\Aspect
 */
class Baz {
	/**
	 * @\AOP\Before("execution(public *(..))")
	 */
	public function beforeAdvice() {}

	/**
	 * @\AOP\After("execution(public *(..))")
	 */
	public function afterAdvice() {}
}
