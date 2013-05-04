<?php

namespace Reflection\AnnotationExpressionMatcher;

use AOP\TestCase;
use Reflection\Annotation\SimpleAnnotation;
use Reflection\Annotation\Annotation;
use Reflection\Annotation\Parsing\SimpleAnnotationExpressionMatcher;
use Reflection\Annotation\Parsing\AnnotationExpression;

class SimpleAnnotationExpressionMatcherTest extends TestCase {
	/** @var SimpleAnnotationExpressionMatcher */
	private $annotationExpressionMatcher;

	protected function setUp() {
		$this->annotationExpressionMatcher = new SimpleAnnotationExpressionMatcher();
	}

	/**
	 * @dataProvider matchDataProvider
	 */
	public function testMatch($annotation, $expression, $expected) {
		$result = $this->annotationExpressionMatcher->match($expression, $annotation);
		$this->assertEquals($expected, $result);
	}

	public function matchDataProvider() {
		return array(
			array(new SimpleAnnotation('AOP\Aspect', array()), new AnnotationExpression('\AOP\Aspect'), true),
			array(new SimpleAnnotation('AOP\Before(execution(public *(..)))', array()), new AnnotationExpression('\AOP\Before(..)'), true),
			array(new SimpleAnnotation('AOP\Before()', array()), new AnnotationExpression('\AOP\Before(..)'), true),
			array(new SimpleAnnotation('AOP\Before', array()), new AnnotationExpression('\AOP\Before(..)'), false),
		);
	}
}
