<?php

namespace Reflection\AnnotationExpressionMatcher;

use AOP\TestCase;

class PregAnnotationExpressionMatcherTest extends TestCase {
	/** @var PregAnnotationExpressionMatcher */
	private $pregAnnotationExpressionMatcher;

	protected function setUp() {
		$this->pregAnnotationExpressionMatcher = new PregAnnotationExpressionMatcher();
	}

	public function testMatchSimple() {
		$comment = '/**
			 * @\AOP\Aspect
			 */';

		$matches = $this->pregAnnotationExpressionMatcher->match(new \Reflection\AnnotationExpression('\AOP\Aspect'), $comment);
		$this->assertTrue(is_array($matches));
		$this->assertCount(1, $matches);
		$this->assertEquals('\AOP\Aspect', $matches[0]);
	}

	public function testMatchMulti() {
		$comment = '/**
			 * @\AOP\Aspect
			 * @\AOP\Aspect
			 * @\AOP\Aspect
			 * @\AOP\Aspecting
			 * @\Foo\AOP\Aspecting
			 * @\Foo\AOP\Aspect
			 */';

		$matches = $this->pregAnnotationExpressionMatcher->match(new \Reflection\AnnotationExpression('\AOP\Aspect'), $comment);
		$this->assertTrue(is_array($matches));
		$this->assertCount(3, $matches);
		$this->assertEquals('\AOP\Aspect', $matches[0]);
	}

	public function testMatchParameters() {
		$comment = '/**
			 * @\AOP\Aspect("Parameter")
			 * @\AOP\Aspect
			 * @\AOP\Aspect
			 * @\AOP\Aspecting
			 * @\Foo\AOP\Aspecting
			 * @\Foo\AOP\Aspect
			 */';

		$matches = $this->pregAnnotationExpressionMatcher->match(new \Reflection\AnnotationExpression('\AOP\Aspect("Parameter")'), $comment);
		$this->assertTrue(is_array($matches));
		$this->assertCount(1, $matches);
		$this->assertEquals('\AOP\Aspect("Parameter")', $matches[0]);
	}
}
