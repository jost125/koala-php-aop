<?php

namespace Reflection\AnnotationResolver;

use AOP\TestCase;
use ReflectionClass;

class CommentParserAnnotationResolverTest extends TestCase {

	/** @var CommentParserAnnotationResolver */
	private $commentParserAnnotationResolver;

	/** @var \PHPUnit_Framework_MockObject_MockObject */
	private $annotationExpressionMatcherMock;

	protected function setUp() {
		$this->annotationExpressionMatcherMock = $this->createMock('\Reflection\AnnotationExpressionMatcher');
		$this->commentParserAnnotationResolver = new CommentParserAnnotationResolver($this->annotationExpressionMatcherMock);
	}

	public function testHasClassAnnotation() {
		require_once __DIR__ . '/CommentParserAnnotationResolverTest/Foo.php';

		$reflectionClass = new ReflectionClass('\CommentParserAnnotationResolverTest\Foo');
		$annotationExpression = new \Reflection\AnnotationExpression('\AOP\Aspect');
		$comment = '/**
 * @\AOP\Aspect
 */';

		$this->annotationExpressionMatcherMock->expects($this->once())
			->method('match')
			->with($annotationExpression, $comment)
			->will($this->returnValue(array('\AOP\Aspect')));

		$this->assertTrue($this->commentParserAnnotationResolver->hasClassAnnotation($reflectionClass, $annotationExpression));
	}

	public function testHasNotClassAnnotation() {
		require_once __DIR__ . '/CommentParserAnnotationResolverTest/Bar.php';

		$reflectionClass = new ReflectionClass('\CommentParserAnnotationResolverTest\Bar');
		$annotationExpression = new \Reflection\AnnotationExpression('\AOP\Aspect');
		$comment = '/**
 * @\Some\AOP\Aspect
 */';

		$this->annotationExpressionMatcherMock->expects($this->once())
			->method('match')
			->with($annotationExpression, $comment)
			->will($this->returnValue(null));

		$this->assertFalse($this->commentParserAnnotationResolver->hasClassAnnotation($reflectionClass, $annotationExpression));
	}

	public function testGetMethodsHavingAnnotation() {
		require_once __DIR__ . '/CommentParserAnnotationResolverTest/Baz.php';

		$reflectionClass = new ReflectionClass('\CommentParserAnnotationResolverTest\Baz');
		$annotationExpression = new \Reflection\AnnotationExpression('\AOP\Before|\AOP\After');

		$this->annotationExpressionMatcherMock->expects($this->exactly(2))
			->method('match')
			->will($this->returnValue(true));

		$methods = $this->commentParserAnnotationResolver->getMethodsHavingAnnotation($reflectionClass, $annotationExpression);

		$this->assertCount(2, $methods);
		$this->assertEquals('beforeAdvice', $methods[0]->getName());
		$this->assertEquals('afterAdvice', $methods[1]->getName());
	}

}
